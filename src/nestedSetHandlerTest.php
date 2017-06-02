<?php

namespace Assignment\Test;

use PHPUnit\Framework\TestCase;
use Assignment\Core\NestedSetHandler;

require_once __DIR__ . '/nestedSetHandler.php';

final class NestedSetHandlerTest extends TestCase
{
    public function testRequestHandling()
    {
        $this->suppressOutputs();
        $nsHandler = NestedSetHandler::getInstance();
        // tests base use case
        $nodes = $nsHandler->handleRequest(5, 'Italian');
        $this->assertEquals(count($nodes), 11);
    }

    /**
     * This is to avoid standard output responses during test executions
     */
    public function suppressOutputs()
    {
        // Suppress  output to console
        $this->setOutputCallback(function() {});
    }
}
