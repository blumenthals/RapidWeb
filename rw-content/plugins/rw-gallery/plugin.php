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
        echo "<script src='" . $this->baseURL . 'colorbox/colorbox/jquery.colorbox-min.js' . "'></script>";
        echo "<link rel='stylesheet' href='" . $this->baseURL . 'colorbox.css' . "'>";
        echo "<script async>
            jQuery(function($) {
                $('.rwgallery a').colorbox({
                    rel: 'group1',
                    maxWidth: '90%',
                    maxHeight: '90%',
                    next: 'Next',
                    previous: 'Previous',
                    fixed: true
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
