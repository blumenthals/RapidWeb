<?php

namespace RapidWeb {
    abstract class Action {
        public $request;
        public $response;
        public $content = NULL;

        public function __construct() {
        }

        public function getPresentation() {
            if($this->content) return $this->content;
        }

        abstract public function execute(Request $request, Response $response);
    }
}
