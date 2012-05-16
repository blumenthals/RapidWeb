<?php

class RWPlugin extends RWBundle {
    protected $rapidweb;
    protected $baseURL;

    public function __construct(RapidWeb $rapidweb) {
        $this->rapidweb = $rapidweb;
    }

    public function setBaseURL($url) {
        $this->baseURL = $url;
    }

    public static function initialize($rapidweb) {
        new static($rapidweb);
    }

    public function loadJavascript($script) {
        if (file_exists(__DIR__).$script) {
            echo "<script src='".$this->assetURL($script)."'></script>";
        } else {
            return parent::loadJavascript($script);
        }
    }

    public function assetURL($path) {
        return $this->baseURL . $path;
    }
}
