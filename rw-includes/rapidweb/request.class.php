<?php

namespace RapidWeb {

    class Request implements \ArrayAccess {
        public $params = array();
        public $headers = array();
        public $root;
        public $path;
        public $method;

        public function __construct($phprequest, $phpserver, $phpfiles) {
            foreach($phprequest as $k => $v) {
                $this->params[$k] = $v;
            }
            foreach($phpfiles as $k => $v) {
                $this->params[$k] = new FileUpload($v);
            }
            foreach($phpserver as $k => $v) {
                if(preg_match('/^HTTP_.*|CONTENT_TYPE|CONTENT_LENGTH/', $k, $matches)) {
                    $header = join('-', array_map('ucfirst', explode('_', strtolower(str_replace('HTTP_', '', $matches[0])))));
                    $this->headers[$header] = $v;
                }
            }

            /// @todo make this pluggable
            if (preg_match("@^application/json(;.*)?@", $this->headers['Content-Type'])) {
                $this->content = json_decode(file_get_contents('php://input'));
            } else {
                $this->content = file_get_contents('php://input');
            }

            $this->method = strtoupper($phpserver['REQUEST_METHOD']);

            $this->root = dirname($phpserver['SCRIPT_NAME']);
            if($this->root{strlen($this->root) - 1} != '/') $this->root .= '/';
            $this->path = preg_replace('!^'.preg_quote($this->root, '!').'!', '', $phpserver['REQUEST_URI']);
        } 

        public function offsetExists($offset) {
            return isset($this->params[$offset]);
        }

        public function offsetGet($offset) {
            if(array_key_exists($offset, $this->params)) {
                return $this->params[$offset];
            } else {
                return NULL;
            }
        }

        public function offsetSet($offset, $value) {
            $this->params[$offset] = $value;
        }

        public function offsetUnset($offset) {
            unset($this->params[$offset]);
        }

        public function getRoot() {
            return $this->root;
        }

    }
}
