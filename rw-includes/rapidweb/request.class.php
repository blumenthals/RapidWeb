<?php

namespace RapidWeb {

    class Request implements \ArrayAccess {
        public $params = array();
        public $headers = array();
        public $root;

        public function __construct($phprequest, $phpserver, $phpfiles) {
            foreach($phprequest as $k => $v) {
                $this->params[$k] = $v;
            }
            foreach($phpfiles as $k => $v) {
                $this->params[$k] = new FileUpload($v);
            }
            foreach($phpserver as $k => $v) {
                if(preg_match('/^HTTP_(.*)/', $k, $matches)) {
                    $header = join('-', array_map('ucfirst', explode('_', strtolower($matches[1]))));
                    $this->headers[$header] = $v;
                }
            }

            $this->root = dirname($phpserver['SCRIPT_NAME']);
            if($this->root{strlen($this->root) - 1} != '/') $this->root .= '/';
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
