<?php

class RWGallery extends RWPlugin {
    public function __construct($rapidweb) {
        parent::__construct($rapidweb);
        $rapidweb->registerPagetype('rwgallery', $this);
    }

    public function getPageTypeName() {
        return 'Gallery';
    }

    public function do_editor_head() {
        $this->loadJavascript('rw-gallery-edit.js');
        $this->loadJavascript('underscore-min.js');
        $this->loadJavascript('knockout/build/output/knockout-latest.js');
        echo "<link rel='stylesheet' href='{$this->baseURL}/rw-gallery-edit.css'>";
    }

    public function do_head($page) {
        $this->loadJavascript('colorbox/colorbox/jquery.colorbox.js');
        echo "<link rel='stylesheet' href='" . $this->baseURL . '/colorbox.css' . "'>";
        echo "<script async>
            jQuery(function($) {
                $('.rwgallery a').colorbox({
                    rel: 'group1',
                    photo: true,
                    maxWidth: '90%',
                    maxHeight: '90%',
                    next: 'Next',
                    previous: 'Previous',
                    fixed: true,
                    current: '{current}/{total}'
                }) 
            })
        </script>";
    }

    public function the_content($page) {
        echo "<div class='rwgallery'>";
        foreach($page->gallery as $image) {
            $caption = htmlspecialchars($image->caption);
            $description = htmlspecialchars($image->description);
            echo "<a href='".$image->image."' title='{$description}'>";
            echo "<img src='".$image->thumbnail."' title='{$caption}'>{$caption}";
            echo "</a>";
        }
        echo "</div>";
    }

    public function the_editor_content($view) {
        //@todo html encode
    ?>
        <script id="photoDetailsEdit" type="text/template">
            <div class='photoDetailsEdit'>
                <div class='gallery-tile image-tile'>
                  <img src='<%= thumbnail %>'>
                </div>
                <input name='caption' data-bind="value: caption" value='' placeholder='Caption'>
                <textarea name='description' data-bind="value: description" placeholder='Description'></textarea>
                <button data-bind='click: close' class='ok-button'>Ok</button>
            </div>
        </script>
        <div class='uploader'>
          <iframe style='display: none' id='upload_target' name='upload_target'></iframe>
          <form action='<?php echo $view->getScriptURL(); ?>' target='upload_target' method='post' enctype='multipart/form-data'>
            <div class='file-upload'>
              Upload a file
              <div class='native-element'>
                <input type='file' name='img'>
              </div>
              <input type='hidden' name='pagename' value='<?php echo $view->page->pagename; ?>'>
              <input type='hidden' name='command' value='upload_image_ajax'>
            </div>
            <div class='spinner'><img src='<?php echo "{$this->baseURL}loader.gif" ?>'></div>
            <div class='error'></div>
          </form>
        </div>
        <div class='instructions'>
           <h3 style="border-bottom: solid 2px #C54808;">Instructions</h3>
           <div class="col">
             <li>Drag a photo to re-arrange.</li>
             <li>Click the 'X' on a picture to remove it.</li>
             <li>JPEG, GIF and PNG files can all be uploaded.</li>
             <li>Any photo over 1,250 x 1,250 pixels will be resized.</li>
             <li>Be patient uploading. Uploading photos directly from a digital camera can be take some time.</li>
           </div>
        </div>
        <div style="clear:both; padding-bottom:10px;"><!-- --></div>
        <div class='images'>
          <div class='insertion-point'></div>
        </div>
        <div style="float: right; height: 40px;">
          <button class="cancel" name='cancel' onclick="history.go(-1)"></button>
          <button class="save" name='save'></button>
        </div>
        
    <?php
    }

}

$this->registerPlugin('RWGallery');
