<?php

use PHPUnit\Framework\TestCase;
use Assignment\Core\DbMngr;

require_once __DIR__ . '/dbMngr.php';

final class NestedSetHandlerTest extends TestCase
{
    public function testConstructor()
    {
        $dbmngr = DbMngr::getInstance();
        $this->assertInstanceOf(DbMngr::class, $dbmngr);
    }

    /**
     * Test queries.
     * WARNING: this test is heavily dependant on actual data, not a proper unit test!
     */
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
        $this->assertEquals($queryResult, 'Uh oh...Something\'s wrong with your query...Are you trying to inject?!?');
        $queryResult = $dbmngr->executeQuery('SELECT * FROM \'node_tree;');
        $this->assertEquals($queryResult, 'Uh oh...Something\'s wrong with your query...Are you trying to inject?!?');
    }

    public function testQuerySingleNode()
    {
        $dbmngr = DbMngr::getInstance();
        $nodeInfo = $dbmngr->queryNode(1);
        $testData = array(
            'idNode' => '1',
            'level'  => '2',
            'iLeft'  => '2',
            'iRight' => '3'
        );
        $this->assertEquals($nodeInfo, $testData);
    }
}
