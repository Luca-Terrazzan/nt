<?php
namespace Assignment\Core;

use Assignment\Core\DbMngr;
use Assignment\Core\Response;

require_once __DIR__ . '/dbMngr.php';
require_once __DIR__ . '/response.php';

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
    public function handleRequest($node_id, $language, $search_keyword = '', $page_num = 0, $page_size = 100)
    {
        $errorType = $this->validateParams($node_id, $language, $search_keyword, $page_num, $page_size);
        if ($errorType < 0) {
            $this->response->error($errorType)->send();
            return false;
        }

        $parent = $this->dbMngr->queryNode($node_id);
        $nodes = $this->dbMngr->queryChildren($parent['iLeft'], $parent['iRight'], $language);
        $this->response->success($nodes)->send();
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
        if ($languageError < 0) {
            return $languageError;
        }
        $nodeError = $this->validateNodeId($node_id);
        if ($nodeError < 0) {
            return $nodeError;
        }
        return 1;
    }

    /**
     * Validation of the node_id parameter,
     * it is considered valid if node_id belongs to [0, inf]
     *
     * @param String $node_id
     * @return -2 => invalid id (e.g. NaN, negative id)
     *         -1 => missing id
     *          1 => OK
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
            return 1;
        }
        $int_id = (int) $node_id;
        // check if the node_id contains non-numeric characters
        if ((string)$int_id !== $node_id || $int_id < 0) {
            return -2;
        }
        return 1;
    }

    /**
     * Validates the language parameter, for now any non-empty
     * string is fine
     *
     * @param string $language
     * @return -1 => missing parameter
     *          1 => OK
     */
    private function validateLanguage($language)
    {
        if (empty($language)) {
            return -1;
        }
        return 1;
    }
}
