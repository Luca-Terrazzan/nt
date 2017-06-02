<?php
/**
 * Assignment entry point
 * @author     Luca Terrazzan <luca.terraz@gmail.com>
 */
use Assignment\Core\NestedSetHandler;

require_once __DIR__ . '/src/nestedSetHandler.php';

$nset = NestedSetHandler::getInstance();
$nset = $nset->handleRequest(
    $_GET['node_id'],
    $_GET['language'],
    $_GET['search_keyword'],
    $_GET['page_num'],
    $_GET['page_size']
);
