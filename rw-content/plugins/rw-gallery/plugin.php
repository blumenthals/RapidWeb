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
        echo "<script src='{$this->baseURL}rw-gallery-edit.js'></script>";
        echo "<link rel='stylesheet' href='{$this->baseURL}rw-gallery-edit.css'>";
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

    public function the_editor_content($view) {
    ?>
      <div id='rwgallery_editor' class='rapidweb-editor'>
        <section class='details-box'>
          <h3 class='details-box-show'>
            <img src="<?php bloginfo('template_directory'); ?>/../default/admin/arrow-down.gif" align="absmiddle"> Instructions
          </h3>
          <h3 class='details-box-hide'>
            <img src="<?php bloginfo('template_directory'); ?>/../default/admin/arrow-over.gif" align="absmiddle"/> Instructions
          </h3>
          <div class='details'>
            <p>Drag to re-arrange.</p>
            <p>Click the X on a picture to remove it.</p>
            <p>JPEG, GIF and PNG files all work.</p>
            <p>Any picture over 1000x1000 pixels is resized.</p>
            <p>Be patient uploading. Sending full-size pictures can be slow!</p>
          </div>
        </section>
        <div class='gallery-tile upload-tile' id='upload-tile'>
          <iframe style='display: none' id='upload_target' name='upload_target'></iframe>
          <form action='<?php echo $view->getScriptURL(); ?>' target='upload_target' method='post' enctype='multipart/form-data'>
            <div class='file-upload'>
              Upload a file
              <input type='file' name='img'>
              <input type='hidden' name='pagename' value='<?php echo $view->page->pagename; ?>'>
              <input type='hidden' name='command' value='upload_image_ajax'>
            </div>
            <div class='spinner'><img src='<?php echo "{$this->baseURL}loader.gif" ?>'></div>
            <div class='error'></div>
          </form>
        </div>

      </div>
    <?php
    }

}

$this->registerPlugin('RWGallery');
