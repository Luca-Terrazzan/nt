<?php
namespace Assignment\Core;

/**
 * Config file loader, reads from a file (config.json)
 *
 * @author     Luca Terrazzan <luca.terraz@gmail.com>
 */
class Config
{
    private $data;
    private static $instance;
    private static $config_filename = 'config.json';

    /************************
     * Singleton constructor
     */
    private function __construct()
    {
        $this->data = json_decode(file_get_contents(__DIR__ . '/' . self::$config_filename), true);
    }
    public static function readConfig()
    {
        if (self::$instance == null) {
            self::$instance = new Config();
        }
        return self::$instance;
    }
    //***********************

    /**
     * Gets a value from the config data
     *
     * @param  string    $key
     * @return string    the value
     */
    public function get(string $key)
    {
        if (!isset($this->data[$key])) {
            echo 'Key ' . $key . ' not found! Please check the configuration .json';
        }
        return $this->data[$key];
    }
}
