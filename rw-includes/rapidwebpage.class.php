<?php

class RapidWebPage {
    private $hash;
    public function __construct($hash) {
        if(is_string($hash['noindex'])) $hash['noindex'] = ($hash['noindex'] === 'true' or $hash['noindex'] === "1" ? true : false);
        $this->hash = $hash;
    }

    public function __get($key) {
        if($key == 'content') {
            if(!$this->hash[$key]) return '';
            return join("\n", $this->hash[$key]);
        } else {
            return $this->hash[$key];
        }
    }

    public function toJSON() {
        return json_encode($this->hash);
    }
}
