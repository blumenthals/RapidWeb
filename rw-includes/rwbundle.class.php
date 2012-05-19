<?php

abstract class RWBundle {
    public function loadJavascript($script) {
        if ($this->hasAsset($script)) {
            echo "<script src='".$this->assetURL($script)."'></script>";
        } else {
            throw new Exception("Can't find script '$script'");
        }
    }

    public function hasAsset($asset) {
        return file_exists($this->assetDir()).$script;
    }

    abstract public function assetDir();
}
