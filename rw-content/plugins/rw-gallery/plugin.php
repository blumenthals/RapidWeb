<?php

class RWGallery extends RWPlugin {
    public function __construct($rapidweb) {
        parent::__construct($rapidweb);
        $rapidweb->register_pagetype('rwgallery', $this);
    }

    public function getPageTypeName() {
        return 'Gallery';
    }

    public function do_editor_head() {
        echo "<script src='{$this->baseURL}/rw-gallery-edit.js'></script>";
        echo "<link rel='stylesheet' href='{$this->baseURL}/rw-gallery-edit.css'>";
    }

    public function do_head($page) {
        echo "<script src='" . $this->baseURL . '/colorbox/colorbox/jquery.colorbox-min.js' . "'></script>";
        echo "<link rel='stylesheet' href='" . $this->baseURL . '/colorbox.css' . "'>";
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

    public function the_editor_content($view) {
    ?>
        <div class='uploader'>
          <iframe style='display: none' id='upload_target' name='upload_target'></iframe>
          <form action='<?php echo $view->getScriptURL(); ?>' target='upload_target' method='post' enctype='multipart/form-data'>
            <div class='file-upload'>
              Upload a file
              <input type='file' name='img'>
              <input type='hidden' name='pagename' value='<?php echo $view->page->pagename; ?>'>
              <input type='hidden' name='command' value='upload_image_ajax'>
            </div>
            <div class='spinner'><img src='<?php echo "{$this->baseURL}/loader.gif" ?>'></div>
            <div class='error'></div>
          </form>
        </div>
        <div class='instructions'>
           <h3 style="border-bottom: solid 2px #C54808;">Instructions</h3>

           <li>Drag a photo to re-arrange.</li>
           <li>Click the 'X' on a picture to remove it.</li>
           <li>JPEG, GIF and PNG files can all be uploaded.</li>
           <li>Any photo over 1000 x 1000 pixels will be resized.</li>
           <li>Be patient uploading. Uploading photos directly<br />
             from a digital camera can be take some time.</li>
        </div>
        <div style="clear:both; padding-bottom:10px;"><!-- --></div>
        <div class='images'>
          <div class='insertion-point'></div>
        </div>
        
    <?php
    }

}

$this->registerPlugin('RWGallery');
