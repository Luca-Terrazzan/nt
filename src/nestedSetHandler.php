<?php
namespace Assignment\Core;

use Assignment\DataBase\Core;

require_once __DIR__ . '/dbMngr.php';

/**
 * Assignment nested set handler.
 * @author     Luca Terrazzan <luca.terraz@gmail.com>
 */
class NestedSetHandler
{
    private static $instance;
    private $request;
    private $dbMngr;

    private function __construct()
    {
        $this->params = $_GET;
        $this->dbMngr = DbMngr::getInstance();
    }
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new NestedSetHandler();
        }
        return self::$instance;
    }

    public function handleRequest($node_id, $language, $search_keyword = '', $page_num = 0, $page_size = 100)
    {
        //
    }
}
