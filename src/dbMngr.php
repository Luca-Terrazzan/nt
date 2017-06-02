<?php
namespace Assignment\Core;

use Assignment\Core\Config;
use Assignment\Core\Utils;
use \MySQLi;

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/utils.php';

/**
 * Superlightweight database manager
 * @author     Luca Terrazzan <luca.terraz@gmail.com>
 */
class DbMngr
{
    private static $instance;
    private $config;
    private $db;

    /************************
     * Singelton constructor
     */
    private function __construct()
    {
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

    public function executeQuery($query)
    {
        $result = $this->db->query($query);
        if ($result) {
            $retVal = $result->fetch_all(MYSQLI_ASSOC);
            $result->close();
            return $retVal;
        } else {
            // TODO: should be handled with a custom exception
            return 'Are you trying to inject?!?';
        }
    }

    public function queryNode($node_id)
    {
        $node_id = $this->cleanParam($node_id);
        $query = 'SELECT * FROM node_tree WHERE idNode = ' . $node_id;
        return $this->executeQuery($query);
    }

    public function queryChildren($iLeft, $iRight, $language)
    {
        $language = $this->cleanParam($language);
        $query = 'SELECT Child.idNode, Trans.nodeName '
            . 'FROM node_tree AS Child, node_tree AS Parent, node_tree_names AS Trans '
            . 'WHERE Child.iLeft > Parent.iLeft AND Child.iRight < Parent.iRight '
            . 'AND Parent.iLeft = ' . $iLeft . ' AND Parent.iRight = ' . $iRight . ' '
            . 'AND Child.idNode = Trans.idNode AND Trans.language = "' . $language . '"';
        return $this->executeQuery($query);
    }

    /**
     * Sanitize a string parameter
     *
     * @param string $param
     * @return string The clean string
     */
    private function cleanParam($param)
    {
        return $this->db->real_escape_string($param);
    }
}
