<?php
/**
 * Assignment entry point
 * @author     Luca Terrazzan <luca.terraz@gmail.com>
 */
use Assignment\DataBase\DbMngr;

require_once __DIR__ . '/src/dbMngr.php';

$db = DbMngr::getInstance();
$db->tableScan("node_tree");
