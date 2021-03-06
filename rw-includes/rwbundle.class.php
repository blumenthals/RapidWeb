<?php

abstract class RWBundle {
    public function loadJavascript($script) {
        if ($this->hasAsset($script)) {
            echo "<script src='".$this->assetURL($script)."'></script>";
        } else {
            throw new Exception("Can't find asset '$script'");
        }
    }

    public function hasAsset($asset) {
        assert('$asset');
        return file_exists($this->assetDir().$asset);
    }

    abstract public function assetDir();
    abstract public function assetURL($asset);
}
