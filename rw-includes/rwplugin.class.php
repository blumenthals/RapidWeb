<?php

class RWPlugin {
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
        /// @todo: Make this look up resources from other bundles
        echo "<script src='".$this->assetURL($script)."'></script>";
    }

    public function assetURL($path) {
        return $this->baseURL . $path;
    }
}
