<?php

namespace RapidWeb {
    class Response {
        public $body;
        public $headers = array();
        private $jsonp = false;

        public function __construct($options = array()) {
            if($options['jsonp']) $this->jsonp = $options['jsonp'];
        }

        public function renderText($body) {
            $this->body = $body;
        }

        public function renderJSON($json) {
            if($this->jsonp) {
                $this->setHeader('Content-Type', 'application/javascript');
                $this->renderText($this->jsonp.'('.json_encode($json).')');
            } else {
                //$this->setHeader('Content-Type', 'application/json');
                $this->setHeader('Content-Type', 'text/plain');
                $this->renderText(json_encode($json));
            }
        }

        public function render($view, $data) {
            ob_start();
            extract($data);
            include(dirname(__FILE__).'/views/'.$view.'.template.php');
            $page = ob_get_contents();
            ob_end_clean();
            $this->renderText($page);
        }

        public function setHeader($header, $value) {
            $this->headers[$header] = $value;
        }

        public function addScript($name) {
            global $TemplateName;
            $templates = array($TemplateName, 'default');
            if($f = rw_find_template($templates, array($name))) {
                echo "<script src='$f'></script>";
            } else {
                throw new Exception("Can't find script $name");
            }
        }

        public function send() {
            foreach($this->headers as $k => $v) {
                header("$k: $v");
            }

            print($this->body);
        }

        public function redirect($url) {
            header('HTTP/1.1 302 Redirect');
            $this->setHeader('Location', $url);
        }
    }
}
