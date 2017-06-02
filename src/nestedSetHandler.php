<?php
namespace Assignment\Core;

use Assignment\Core\DbMngr;
use Assignment\Core\Response;

require_once __DIR__ . '/dbMngr.php';
require_once __DIR__ . '/response.php';

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

    /**
     * Actual request handling
     * @param string $node_id
     * @param string $language
     * @param string $search_keyword
     * @param integer $page_num
     * @param integer $page_size
     * @return TODO:
     */
    public function handleRequest($node_id, $language, $search_keyword = '', $page_num = DEFAULT_PAGE_NUMBER, $page_size = DEFAULT_PAGE_SIZE)
    {
        if ($page_num === null) $page_num = DEFAULT_PAGE_NUMBER;
        if ($page_size === null) $page_size = DEFAULT_PAGE_SIZE;
        $paramErrorType = $this->validateParams($node_id, $language, $search_keyword, $page_num, $page_size);
        if ($paramErrorType) {
            return $this->errorHandler($paramErrorType);
        }

        $parentNode = $this->dbMngr->queryNode($node_id);
        if (!is_array($parentNode) || !isset($parentNode['iLeft']) || !isset($parentNode['iRight'])) {
            // if outcome is a negative number then it is an error code
            return $this-errorHandler($parentNode);
        }
        $nodes = $this->dbMngr->queryChildren($parentNode['iLeft'], $parentNode['iRight'], $language, $page_num, $page_size);
        if (!is_array($nodes)) {
            // if outcome is a negative number then it is an error code
            return $this->errorHandler($nodes);
        }
        $this->response->success($nodes)->send();
        return $nodes;
    }

    /**
     * Param validation
     *
     * @param string $node_id
     * @param string $language
     * @param string $search_keyword
     * @param string $page_num
     * @param string $page_size
     * @return boolean
     */
    private function validateParams($node_id, $language, $search_keyword, $page_num, $page_size)
    {
        $languageError = $this->validateLanguage($language);
        if ($languageError) {
            return $languageError;
        }
        $nodeError = $this->validateNodeId($node_id);
        if ($nodeError) {
            return $nodeError;
        }
        $pageSizeError = $this->validatePageSize($page_size);
        if ($pageSizeError) {
            return $pageSizeError;
        }
        $pageNumError = $this->validatePageNumber($page_num);
        if ($pageNumError) {
            return $pageNumError;
        }
        return 0;
    }

    /**
     * Validation of the node_id parameter,
     * it is considered valid if node_id belongs to [0, inf]
     *
     * @param String $node_id
     * @return -2 => invalid id (e.g. NaN, negative id)
     *         -1 => missing id
     *          0 => OK
     */
    private function validateNodeId($node_id)
    {
        if ($node_id === null || $node_id === '') {
            // does not use empty() to avoid node_id '0' to be treated as invalid
            return -1;
        }
        $node_id = ltrim($node_id, '0');
        // if the id is empty after trim => node_id = 0
        if ($node_id === '') {
            return 0;
        }
        $int_id = (int) $node_id;
        // check if the node_id contains non-numeric characters
        if ((string)$int_id !== $node_id || $int_id < 0) {
            return -2;
        }
        return 0;
    }

    /**
     * Validates the language parameter, for now any non-empty
     * string is fine
     *
     * @param string $language
     * @return -1 => missing parameter
     *          0 => OK
     */
    private function validateLanguage($language)
    {
        if (empty($language)) {
            return -1;
        }
        return 0;
    }

    private function validatePageNumber($pageNumber)
    {
        $intPageNumber = (int) $pageNumber;
        // check if the pageNumber contains non-numeric characters
        if ((string)$intPageNumber !== $pageNumber || $intPageNumber < 0) {
            return -3;
        }
        return 0;
    }

    private function validatePageSize($pageSize)
    {
        $intPageSize = (int) $pageSize;
        // check if the pagesize contains non-numeric characters
        if ((string)$intPageSize !== $pageSize || $intPageSize < 1 || $intPageSize > 1000) {
            return -4;
        }
        return 0;
    }

    private function errorHandler($errorCode)
    {
        $this->response->error($errorCode)->send();
        return false;
    }
}
