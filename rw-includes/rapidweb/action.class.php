<?php

namespace RapidWeb {
    abstract class Action {
        public $request;
        public $response;
        public $content = NULL;
        protected $app;

        public function __construct(\RapidWeb $app) {
            $this->app = $app;
        }

        public function getPresentation() {
            if($this->content) return $this->content;
        }

        abstract public function execute(Request $request, Response $response);
    }
}
