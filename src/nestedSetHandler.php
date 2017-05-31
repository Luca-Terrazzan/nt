<?php
namespace Assignment\Core;

use Assignment\DataBase\DbMngr;

/**
 * Assignment nested set handler.
 * @author     Luca Terrazzan <luca.terraz@gmail.com>
 */
class NestedSetHandler
{
    private $request;
    private $dbMngr;

    public function __construct()
    {
        $this->params = $_GET;
        $this->dbMngr = DbMngr::getInstance();
    }

    public function handleRequest($node_id, $language, $search_keyword = '', $page_num = 0, $page_size = 100)
    {
        //
    }
}
