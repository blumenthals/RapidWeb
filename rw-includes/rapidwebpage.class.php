<?php

class RapidWebPage {
    private $hash;
    public function __construct($hash) {
        $this->hash = $hash;
    }

    public function __get($key) {
        if($key == 'content') {
            return join("\n", $this->hash[$key]);
        } else {
            return $this->hash[$key];
        }
    }

    public function toJSON() {
        return json_encode($this->hash);
    }
}
