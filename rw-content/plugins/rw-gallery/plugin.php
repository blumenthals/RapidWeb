<?php

class RWGallery extends RWPlugin {
    public function getPageTypeName() {
        return 'Gallery';
    }

    public function getEditorScript() {
        return $this->baseURL . 'rw-gallery-edit.js';
    }

}

$this->registerPlugin('RWGallery');
