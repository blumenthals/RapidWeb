<?php

class RWGallery extends RWPlugin {
    public function __construct($rapidweb) {
        parent::__construct($rapidweb);
        $rapidweb->register_pagetype('rwgallery', $this);
    }

    public function getPageTypeName() {
        return 'Gallery';
    }

    public function getEditorScript() {
        return $this->baseURL . 'rw-gallery-edit.js';
    }

}

$this->registerPlugin('RWGallery');
