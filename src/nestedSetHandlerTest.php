<?php

namespace Assignment\Test;

use PHPUnit\Framework\TestCase;
use Assignment\Core\NestedSetHandler;

require __DIR__ . '/nestedSetHandler.php';
/**
 * Test class for the request handling.
 * Note: all parameters are strings, as they come from the $_GET
 * array in api.php
 * FIXME: db mock + dependency stubs
 *
 * @author     Luca Terrazzan <luca.terraz@gmail.com>
 */
final class NestedSetHandlerTest extends TestCase
{
    public function testSimpleRequest()
    {
        $this->suppressOutputs();
        $nsHandler = NestedSetHandler::getInstance();
        // tests base use case
        $nodes = $nsHandler->handleRequest('5', 'Italian');
        $this->assertEquals(count($nodes), 11);
        $this->assertEquals($nodes[2]['name'], 'Managers');
        $this->assertEquals($nodes[5]['children_count'], '4');
    }

    public function testFullRequests()
    {
        $this->suppressOutputs();
        $nsHandler = NestedSetHandler::getInstance();
        // tests more complex requests
        $nodes = $nsHandler->handleRequest('5', 'Italian', null, null, '2');
        $this->assertEquals(count($nodes), 2);
        $this->assertEquals($nodes[1]['name'], 'Supporto tecnico');
        $nodes = $nsHandler->handleRequest('5', 'Italian', 'te');
        $this->assertEquals(count($nodes), 3);
        $this->assertEquals($nodes[2]['node_id'], '7');
        $nodes = $nsHandler->handleRequest('5', 'English', null, '2', '3');
        $this->assertEquals(count($nodes), 3);
        $this->assertEquals($nodes[1]['node_id'], '9');
        $this->assertEquals($nodes[1]['name'], 'Europe');
    }

    public function testLimits()
    {
        $this->suppressOutputs();
        $nsHandler = NestedSetHandler::getInstance();
        // tests more complex requests
        $nodes = $nsHandler->handleRequest('5', 'Korean', null, null, '2');
        $this->assertEquals(count($nodes), 0);
        $nodes = $nsHandler->handleRequest('0', 'Italian', null, null, '2');
        $this->assertEquals(count($nodes), 0);
        $nodes = $nsHandler->handleRequest('0', 'Italian', '3', '10', '2');
        $this->assertEquals(count($nodes), 0);
    }

    public function testErrors()
    {
        $this->suppressOutputs();
        $nsHandler = NestedSetHandler::getInstance();
        // tests more complex requests
        $nodes = $nsHandler->handleRequest('5', '');
        $this->assertEquals($nodes['error'], -1);
        $nodes = $nsHandler->handleRequest('5a', 'Italian');
        $this->assertEquals($nodes['error'], -2);
        $nodes = $nsHandler->handleRequest('5', 'English', 'ma', '-1');
        $this->assertEquals($nodes['error'], -3);
        $nodes = $nsHandler->handleRequest('2', 'Italian', null, 1, '0');
        $this->assertEquals($nodes['error'], -4);
        $nodes = $nsHandler->handleRequest('9', 'English', null, null, '1001');
        $this->assertEquals($nodes['error'], -4);
    }

    /**
     * This is to avoid standard output responses during tests execution
     */
    public function suppressOutputs()
    {
        // Suppress  output to console
        $this->setOutputCallback(
            function () {
                // empty function
            }
        );
    }
}
