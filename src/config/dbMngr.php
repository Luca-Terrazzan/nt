<?php
namespace Assignment\DataBase;

use Assignment\Config\Config;
use \PDO;

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
        $dsn = $this->config['db_type'] . ':host=' . $this->config['endpoint'] . ';dbname=' . $this->config['table'];
        try {
            $this->db = new PDO(
                $dsn,
                $this->config['username'],
                $this->config['password']
            );
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $pdoe) {
            echo 'Database configuration error: ' . $pdoe->getMessage();
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
        // TODO: check for injections
        $query = 'SELECT * FROM ' . $tableName . ' LIMIT 1000';
        $rows = $this->db->query($query);
        // prettyprint every row
        foreach ($rows as $record) {
            echo '<pre>';
            print_r($record);
            echo '</pre>';
        }
    }
}
