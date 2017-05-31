<?php
namespace Assignment\DataBase;

use Assignment\Config\Config;
use \MySQLi;

require_once __DIR__ . '/config.php';

/**
 * Superlightweight database manager
 * @author     Luca Terrazzan <luca.terraz@gmail.com>
 */
class DbMngr
{
    private static $instance;
    private $config;
    private $db;

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
            if ($this->db->connect_error) {
                die('Connection error: ' . $this->db->connect_error);
            }
        } else {
            die('MySQLi not installed!');
        }
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new DbMngr();
        }
        return self::$instance;
    }

    public function tableScan($tableName)
    {
        $query = 'SELECT * FROM ' . $tableName . ' LIMIT 1000';
        $result = $this->executeQuery($query);
        if ($result) {
            var_dump($result);
        }
    }

    private function executeQuery($query)
    {
        $cleanQuery = $this->db->real_escape_string($query);
        $result = $this->db->query($cleanQuery);
        if ($result) {
            $retVal = $result->fetch_all();
            $result->close();
            return $retVal;
        } else {
            die('Uh oh...Something\'s wrong with your query...Are you trying to inject?!?');
        }
    }
}
