<?php
namespace Assignment\Core;

class Config
{
    private $data;
    private static $instance;
    private static $config_filename = 'config.json';

    private function __construct()
    {
        $this->data = json_decode(file_get_contents(__DIR__ . '/config/' . self::$config_filename), true);
    }

    public static function readConfig()
    {
        if (self::$instance == null) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    public function get(string $key)
    {
        if (!isset($this->data[$key])) {
            throw new NotFoundException("Can't find $key in current configuration", 1);
        }
        return $this->data[$key];
    }
}
