<?php
namespace Assignment\Core;

use Assignment\Core\DbMngr;
use Assignment\Core\Response;
use Assignment\Core\ParamValidator;

require_once __DIR__ . '/dbMngr.php';
require_once __DIR__ . '/response.php';
require_once __DIR__ . '/paramValidator.php';

define('DEFAULT_PAGE_SIZE', '100');
define('DEFAULT_PAGE_NUMBER', '0');

/**
 * Assignment nested set handler.
 *
 * @author Luca Terrazzan <luca.terraz@gmail.com>
 */
class NestedSetHandler
{
    private static $instance;
    private $dbMngr;
    private $response;

    /*************************
     * Singleton constructor
     */
    private function __construct()
    {
        $this->dbMngr = DbMngr::getInstance();
        $this->response = new Response();
    }
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new NestedSetHandler();
        }
        return self::$instance;
    }
    //************************

    /**
     * Actual request handling
     * Note: all params are strings since they come from a $_GET array
     * TODO: make a cleaner solution to handle proper param types
     *
     * @param string $node_id
     * @param string $language
     * @param string $search_keyword
     * @param string $page_num
     * @param string $page_size
     * @return array    An array of nodes or array('error' => 'error msg')
     */
    public function handleRequest($node_id, $language, $search_keyword = null, $page_num = DEFAULT_PAGE_NUMBER, $page_size = DEFAULT_PAGE_SIZE)
    {
        // defaults paging params if they are passed as null
        $page_num  = $page_num  === null ? DEFAULT_PAGE_NUMBER : $page_num;
        $page_size = $page_size === null ? DEFAULT_PAGE_SIZE   : $page_size;
        // validate parameters
        $paramErrorType = ParamValidator::validateParams($node_id, $language, $search_keyword, $page_num, $page_size);
        // return an error if something is wrong
        if ($paramErrorType) {
            return $this->errorHandler($paramErrorType);
        }

        // gets data for the parent node
        $parentNode = $this->dbMngr->queryNode($node_id);
        // eventually handle errors
        if (isset($parentNode['error'])) {
            return $this-errorHandler($parentNode['error']);
        } elseif (!isset($parentNode['iLeft']) || !isset($parentNode['iRight'])) {
            // if the node is not found OR it doesn't have necessary fields, return that
            $this->response->success($parentNode)->send();
            return $parentNode;
        }
        // query for the actual node list
        $nodes = $this->dbMngr->queryChildren($parentNode['iLeft'], $parentNode['iRight'], $language, $search_keyword, $page_num, $page_size);
        // error handling if needed
        if (isset($nodes['error'])) {
            return $this->errorHandler($nodes['error']);
        }
        // sends the response
        $this->response->success($nodes)->send();
        return $nodes;
    }

    /**
     * Route errors to the Response helper
     *
     * @param array     $errorCode
     * @return array    Array containing the error code, used in tests
     */
    private function errorHandler($errorCode)
    {
        $this->response->error($errorCode)->send();
        return array('error' => $errorCode);
    }
}
