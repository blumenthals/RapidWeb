<?php

namespace RapidWeb {
    class CommandAction extends Action {
        public $name;
        public $method;

        public function __construct($method, $name) {
            $this->name = $name;
            $this->method = $method;
        }

        public function execute(Request $request, Response $response) {
            $func = 'rw_'.strtoupper($_SERVER['REQUEST_METHOD'])."_".$this->name;
            if (is_callable(array($this, $this->name))) {
                call_user_func(array($this, $this->name), $request, $response);
            } elseif(function_exists($func)) {
                call_user_func($func, $request, $response);
            } else {
                throw new \Exception("Can't find handler for {$this->name} via {$_SERVER['REQUEST_METHOD']}");
            }
        }

        // @todo refactor into the wikipage plugin
        function display_page($request, $response) {
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                global $pagehash; // @todo: refactor display.php

                $p = new Parser($pagehash);
                $html = $p->parse($pagehash['content']);
                $response->renderText($html);
            } else {
                throw new \Exception("Can't post to page");
            }
        }

    }
}
