<?php

class RWAssetBundle extends RWBundle {
    private $dir;
    private $url;

    public function __construct($dir, $url) {
        if ($dir{strlen($dir) - 1} != '/')  $dir .= '/';
        if ($url{strlen($url) - 1} != '/')  $url .= '/';
        $this->dir = $dir;
        $this->url = $url;
    }

    public function assetDir() {
        return $this->dir;
    }

    public function assetURL($asset) {
        if ($this->hasAsset($asset)) {
            return $this->url . $asset;
        } else {
            throw new Exception("Can't find asset '$asset'");
        }
    }

}
