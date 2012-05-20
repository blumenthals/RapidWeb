<?php

abstract class RWPlugin extends RWBundle {
    protected $rapidweb;
    protected $baseURL;
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

    public function assetURL($asset) {
        if ($this->hasAsset($asset)) {
            return $this->baseURL . $asset;
        } else {
            return $this->rapidweb->assetURL($asset);
        }
    }

    public static function initialize($rapidweb) {
        new static($rapidweb);
    }

    public function loadJavascript($script) {
        $this->rapidweb->loadJavascript($this->assetURL($script));
    }
}
