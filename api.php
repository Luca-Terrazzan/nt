<?php

namespace Assignemnt\Core;

use Assignment\Core\NestedSetHandler;

require_once __DIR__ . '/src/nestedSetHandler.php';

/**
 * Assignment entry point
 * TODO: refactor with autoloader
 * TODO: add custom exceptions
 *
 * @author     Luca Terrazzan <luca.terraz@gmail.com>
 */

function handle()
{
    // main handler class
    $nestedSetHandler = NestedSetHandler::getInstance();
    // check for parameters and avoid warnings
    $node_id = isset($_GET['node_id']) ? $_GET['node_id'] : null;
    $language = isset($_GET['language']) ? $_GET['language'] : null;
    $search_keyword = isset($_GET['search_keyword']) ? $_GET['search_keyword'] : null;
    $page_num = isset($_GET['page_num']) ? $_GET['page_num'] : null;
    $page_size = isset($_GET['page_size']) ? $_GET['page_size'] : null;
    // handle requests
    $nestedSetHandler = $nestedSetHandler->handleRequest(
        $node_id,
        $language,
        $search_keyword,
        $page_num,
        $page_size
    );
}

// run
handle();
