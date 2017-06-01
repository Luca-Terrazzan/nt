<?php
namespace Assignment\Core;

/**
 * JSON Response wrapper
 */
class Response
{
    private $json;

    public function __construct()
    {
        $this->json = array();
    }

    public function error($errorMessage)
    {
        $this->clear();
        $this->json['error'] = $errorMessage;
        return $this;
    }

    public function success($nodes)
    {
        $this->clear();
        $this->json['nodes'] = $nodes;
        return $this;
    }

    public function sendResponse()
    {
        echo json_encode($this->json);
        return;
    }

    private function clear()
    {
        $this->json = array();
    }
}
