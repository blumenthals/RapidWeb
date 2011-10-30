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

    public function do_head($page) {
#        echo "<script src='" . $this->baseURL . 'rw-gallery.js' . "'></script>";
        echo "<script src='" . $this->baseURL . 'js/jquery.lightbox-0.5.min.js' . "'></script>";
        #echo "<link rel='stylesheet' href='" . $this->baseURL . 'rw-gallery.css' . "'>";
        echo "<link rel='stylesheet' href='" . $this->baseURL . 'css/jquery.lightbox-0.5.css' . "'>";
        echo "<script async>
            jQuery(function($) {
                $('.rwgallery a').lightBox({
                    imageLoading: '{$this->baseURL}images/lightbox-ico-loading.gif',
                    imageBtnClose: '{$this->baseURL}images/lightbox-btn-close.gif',
                    imageBtnPrev: '{$this->baseURL}images/lightbox-btn-prev.gif',
                    imageBtnNext: '{$this->baseURL}images/lightbox-btn-next.gif',
                    imageBlank: '{$this->baseURL}images/lightbox-blank.gif'
                }) 
            })
        </script>";
    }

    public function the_content($page) {
        echo "<div class='rwgallery'>";
        foreach($page->gallery as $image) {
            echo "<a href='".$image->image."'>";
            echo "<img src='".$image->thumbnail."'>";
            echo "</a>";
        }
        echo "</div>";
    }

}

$this->registerPlugin('RWGallery');
