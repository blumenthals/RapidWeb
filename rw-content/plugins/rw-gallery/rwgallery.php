<?php

class RWGallery {
    public function __construct($rapidweb) {
        $this->rapidweb = $rapidweb;
        $rapidweb->register_pagetype('rw-gallery', $this);
    }

    public function getPageTypeName() {
        return 'Gallery';
    }

    public function getPageTypeEditorScript() {
        return 'rw-gallery.js';
    }

    public static function initialize($rapidweb) {
        new self($rapidweb);
    }
}
