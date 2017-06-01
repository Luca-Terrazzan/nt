<?php

use PHPUnit\Framework\TestCase;
use Assignment\Core\NestedSetHandler;

require_once __DIR__ . '/nestedSetHandler.php';

final class NestedSetHandlerTest extends TestCase
{
    public function testRequestHandling()
    {
        $temp = NestedSetHandler::getInstance();
        // mock
        $this->assertTrue(true);
    }
}
