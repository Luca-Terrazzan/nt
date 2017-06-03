<?php
namespace Assignment\Core;

use Assignment\Core\Config;
use \MySQLi;

require_once __DIR__ . '/config.php';

/**
 * Superlightweight database manager
 * Uses Config class to load db configuration data
 *
 * @author     Luca Terrazzan <luca.terraz@gmail.com>
 */
class DbMngr
{
    private static $instance;
    private $config;
    private $db;

    /************************
     * Singleton constructor
     * TODO: replace die()s with proper handling
     */
    private function __construct()
    {
        // load db details
        $this->config = Config::readConfig()->get('db');
        if (function_exists('mysqli_connect')) {
            $this->db = new MySQLi(
                $this->config['endpoint'],
                $this->config['username'],
                $this->config['password'],
                $this->config['schema'],
                $this->config['port']
            );
            $this->db->set_charset('utf8');
            if ($this->db->connect_error) {
                die('Connection error: ' . $this->db->connect_error);
            }
        } else {
            die('MySQLi not installed!');
        }
    }
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new DbMngr();
        }
        return self::$instance;
    }
    //***********************

    /**
     * Executes a query based on $query param
     * Note: sanititzing is NOT handled here, this should be a PRIVATE method.
     *       It is public just to aid the test class!
     *
     * @param [type] $query
     * @return void
     */
    /* private */ public function executeQuery($query)
    {
        $result = $this->db->query($query);
        if ($result) {
            $retVal = $result->fetch_all(MYSQLI_ASSOC);
            $result->close();
            return $retVal;
        } else {
            // TODO: should be handled with a custom exception
            // $result->error holds any error message related to the query
            return array(
                // Are you trying to inject?!?
                'error' => -6
            );
        }
    }

    /**
     * Gets data about a single node of the tree
     *
     * @param  string    $node_id
     * @return array     Assoc array with all db columns for the node
     */
    public function queryNode($node_id)
    {
        $node_id = $this->cleanParam($node_id);
        // builds the query
        $query = 'SELECT * FROM node_tree WHERE idNode = ' . $node_id;
        $node = $this->executeQuery($query);
        if (is_array($node) && isset($node[0])) {
            // return first node, should never find more than one.
            return $node[0];
        } else {
            return $node;
        }
    }

    /**
     * Builds the query to fetch node children's data.
     * Note: the children count for each node is based on the assumption that each node
     * takes the smallest interval possible (that is, for leaves, iRight = iLeft + 1, while for other nodes
     * the difference is the smallest amount possible to contain its children).
     * FIXME: manage nodes without translations, they are being skipped atm
     * TODO: validation for empty pages (e.g. page_num > last page), query builder (really)
     * TODO: switch to full text search for the search_keyword stuff
     * TODO: handle charsets (e.g. try search_keyword = à)
     *
     * @param int $iLeft
     * @param int $iRight
     * @param string $language
     * @return array Array of nodes
     */
    public function queryChildren($iLeft, $iRight, $language, $searchKeyword = null, $pageNum = 0, $pageSize = 100)
    {
        $language      = $this->cleanParam($language);
        $searchKeyword = $this->cleanParam($searchKeyword);
        // query building for the different clauses
        $select = 'SELECT Child.idNode AS node_id, Trans.nodeName AS name, '
            . 'ROUND((Child.iRight - Child.iLeft + 1) / 2) AS children_count '
            . 'FROM node_tree AS Child, node_tree AS Parent, node_tree_names AS Trans';
        $where = ' WHERE Child.iLeft > Parent.iLeft AND Child.iRight < Parent.iRight '
            . 'AND Parent.iLeft = ' . $iLeft . ' AND Parent.iRight = ' . $iRight . ' '
            . 'AND Child.idNode = Trans.idNode AND Trans.language = "' . $language . '"';
        // add text search if needed
        if (is_string($searchKeyword)) {
            $where .= ' AND Trans.nodeName LIKE \'%' . $searchKeyword . '%\'';
        }
        $limit = ' LIMIT ' . $pageSize * $pageNum . ', ' . $pageSize;
        // compose the query
        $query = $select . $where . $limit;
        // execute
        return $this->executeQuery($query);
    }

    /**
     * Sanitize a string parameter
     *
     * @param  string    $param
     * @return string    The clean string
     */
    private function cleanParam($param)
    {
        return $this->db->real_escape_string($param);
    }
}
