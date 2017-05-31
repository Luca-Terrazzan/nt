<?php
namespace Assignment\Core;

/**
 * Assignment nested set handler.
 * @author     Luca Terrazzan <luca.terraz@gmail.com>
 */
class NestedSetHandler
{
    private $params;

    public function __construct()
    {
        $this->params = $_GET;
    }
}
