<?php

class RWAssetBundle extends RWBundle {
    private $dir;

    public function __construct($dir) {
        if ($dir{strlen($dir) - 1} != '/')  $dir .= '/';
        $this->dir = $dir;
    }

    public function assetDir() {
        return $dir;
    }
}
