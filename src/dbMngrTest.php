<?php

namespace Assignment\Test;

use PHPUnit\Framework\TestCase;
use Assignment\Core\DbMngr;

require_once __DIR__ . '/dbMngr.php';

/**
 * Tests for DbMngr
 * FIXME: db mock + dependency stubs
 *
 * @author     Luca Terrazzan <luca.terraz@gmail.com>
 */
final class DbMngrTest extends TestCase
{
    public function testConstructor()
    {
        $dbmngr = DbMngr::getInstance();
        $this->assertInstanceOf(DbMngr::class, $dbmngr);
    }

    public function testQuery()
    {
        $dbmngr = DbMngr::getInstance();
        $queryResult = $dbmngr->executeQuery('SELECT * FROM node_tree');
        $this->assertEquals(count($queryResult), 12);
    }

    public function testInjectionSafety()
    {
        $dbmngr = DbMngr::getInstance();
        $queryResult = $dbmngr->executeQuery('SELECT * FROM node_tree; SELECT * FROM node_tree_names');
        $this->assertEquals($queryResult, array("error" => -6));
        $queryResult = $dbmngr->executeQuery('SELECT * FROM \'node_tree;');
        $this->assertEquals($queryResult, array("error" => -6));
    }

    public function testQuerySingleNode()
    {
        // tests for base use case
        $dbmngr = DbMngr::getInstance();
        $nodeInfo = $dbmngr->queryNode(1);
        $testData = array(
            'idNode' => '1',
            'level'  => '2',
            'iLeft'  => '2',
            'iRight' => '3'
        );
        $this->assertEquals($nodeInfo, $testData);

        // tests for invalid params
        $nodeInfo = $dbmngr->queryNode(-1);
        $this->assertEquals($nodeInfo, array());
        $nodeInfo = $dbmngr->queryNode('; TOTALLY NOT AN INJECTION');
        $this->assertEquals($nodeInfo, array("error" => -6));
    }

    public function testQueryChildren()
    {
        $dbmngr = DbMngr::getInstance();
        // tests for base use case
        $nodes = $dbmngr->queryChildren(1, 24, 'Italian');
        $this->assertEquals(count($nodes), 11);
        // tests leaf
        $nodes = $dbmngr->queryChildren(4, 5, 'Italian');
        $this->assertEquals(count($nodes), 0);
        // tests wrong params
        $nodes = $dbmngr->queryChildren(1, 5, 'Italian; SELECT * FROM node_tree;');
        $this->assertEquals(count($nodes), 0);
    }
}
