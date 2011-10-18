<?php

class RWGallery {
    public function __construct($rapidweb) {
        $this->rapidweb = $rapidweb;
        $rapidweb->register_pagetype('rw-gallery', $this);
    }

    public function getPageTypeName() {
        return 'Gallery';
    }

    public static function setupGallery($rapidweb) {
        new RWGallery($rapidweb);
    }
}

$RapidWeb->on('init', array('RWGallery', 'setupGallery'));
