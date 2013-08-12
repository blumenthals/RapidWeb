<?php

namespace Rapidweb {
    class Page {
        private $hash;
        public function __construct($hash) {
            if(is_string($hash['noindex'])) $hash['noindex'] = ($hash['noindex'] === 'true' or $hash['noindex'] === "1" ? true : false);
            $this->hash = $hash;
        }

        public function __get($key) {
            if($key == 'content') {
                if(!$this->hash[$key]) return '';
                if(is_array($this->hash[$key])) $this->hash[$key] = join("\n", $this->hash[$key]);
                return $this->hash[$key];
            } else {
                return $this->hash[$key];
            }
        }

        public function toJSON() {
            return json_encode($this->hash);
        }
    }
}
