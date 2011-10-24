<?php

class RWPlugin {
    protected $rapidweb;
    protected $baseURL;

    public function __construct(RapidWeb $rapidweb) {
        $this->rapidweb = $rapidweb;
        $rapidweb->register_pagetype('rw-gallery', $this);
    }

    public function setBaseURL($url) {
        $this->baseURL = $url;
    }

    public static function initialize($rapidweb) {
        new static($rapidweb);
    }
}
