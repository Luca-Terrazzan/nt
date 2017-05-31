<?php
    // API
    use Assignment\Core\Config;

    require_once __DIR__ . '/config.php';

    $dbConfig = Config::readConfig();
    echo $dbConfig->get('db')['port'];
