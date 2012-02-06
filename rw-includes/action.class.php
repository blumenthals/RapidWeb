<?php

class Action {
    public $request;
    public $response;
    public $content = NULL;
    public $name;
    public $method;

    public function __construct($method, $name) {
        $this->name = $name;
        $this->method = $method;
    }

    public function getPresentation() {
        if($this->content) return $this->content;
    }
}
