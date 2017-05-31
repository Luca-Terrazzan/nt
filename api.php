<?php
// API
use Assignment\DataBase\DbMngr;

require_once __DIR__ . '/src/config/dbMngr.php';

$db = DbMngr::getInstance();
$db->tableScan('node_tree');
