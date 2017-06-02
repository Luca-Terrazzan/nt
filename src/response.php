<?php
namespace Assignment\Core;

require_once __DIR__ . '/config.php';

/**
 * JSON Response wrapper
 */
class Response
{
    private $json;
    private $errorMsg;

    public function __construct()
    {
        $this->json = array();
        $this->errorMsg = Config::readConfig()->get('error_msg');
    }

    public function error($errorType)
    {
        $this->clear();
        $this->json['error'] = $this->errorMsg[$errorType];
        return $this;
    }

    public function success($nodes)
    {
        $this->clear();
        $this->json['nodes'] = $nodes;
        return $this;
    }

    public function send()
    {
        echo json_encode($this->json);
        return;
    }

    private function clear()
    {
        $this->json = array();
    }
}
