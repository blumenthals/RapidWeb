<?php

abstract class RWPlugin extends RWBundle {
    protected $rapidweb;
    private $baseURL;
    private $baseDir;

    public function __construct(RapidWeb $rapidweb) {
        $this->rapidweb = $rapidweb;
    }

    public function setBaseDir($dir) {
        if ($dir{strlen($dir) - 1}  != '/') $dir .= '/';
        $this->baseDir = $dir;
        $this->baseURL = $this->rapidweb->urlForPath($dir);
    }

    public function assetDir() {
        return $this->baseDir;
    }

    public function assetURL($path) {
        return $this->baseURL . $path;
    }

    public static function initialize($rapidweb) {
        new static($rapidweb);
    }

    public function loadJavascript($script) {
        if ($this->hasAsset($script)) {
            echo "<script src='".$this->assetURL($script)."'></script>";
        } else {
            $this->rapidweb->loadJavascript($script);
        }
    }

}
