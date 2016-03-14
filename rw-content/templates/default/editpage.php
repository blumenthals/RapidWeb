<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Edit: <?php echo $this->page->pagename; ?></title>
<meta name="author" 		content="Mike Robertson, Blumenthals.com">
<meta name="copyright" 		content="Blumenthals.com">
<meta name="language" 		content="en-us">
<meta name="Classification" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php $this->do_head(); ?>

<link rel='rapidweb-admin' href='<?php echo $this->getScriptURL() ?>'>
<script>
  <?php /** @todo rcdata escape the json */ ?>
  var pagedata = <?php echo $this->page->toJSON(); ?>;
</script>
<link href='http://fonts.googleapis.com/css?family=Alegreya+Sans:100,300,400,500,700,800,900' rel='stylesheet' type='text/css'>
<link href="<?php bloginfo('template_directory'); ?>/../default/browse.css" rel="stylesheet" />
<link href="<?php bloginfo('template_directory'); ?>/color.css" rel="stylesheet" />
<link href="<?php bloginfo('template_directory'); ?>/editpage.css" rel="stylesheet" />

<!-- jQuery library (served from Google) -->
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>

</head>

<body>
<header class="mainheader">
  <div class="pagewidth">
  <div class="logo"><a href="<?php echo $this->getScriptURL(); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/logo.png"></a></div>
    <div id="main_nav">
      Editing: <span class='rapidweb-page-title'><?php echo ($this->page->title ? $this->page->title : $this->page->pagename); ?>
    </div>
  </div>
</header>

<div class="maincontent">
  <div class="pagewidth">
    <aside class="sidebar">
      <div class="call_to_action">
        <?php $this->the_pagetype_selector(); ?>
      </div>
      <article class="top_sidebar">
        <a class="upload" onClick="window.open('rw-admin/upload.php','ImageUpload',' width=551, height=494, resizable=yes')" title="Upload an Image from your computer"></a>
        <a class="meta" onClick="window.open('<?php echo $this->getScriptURL(); ?>?settings','Settings',' width=551, height=494, resizable=yes')" title="Edit Default Meta Tags"></a>
      </article>
    </aside>
    <div class="content">
	    <?php
          foreach($this->rapidweb->getPageTypes() as $slug => $pageType) {
            echo "<div id='{$slug}_editor' class='rapidweb-editor'>";
              $pageType->the_editor_content($this);
            echo "</div>";
          }
		  $this->do_editor_settings();
        ?>
      	<?php $this->do_foot(); ?>
    </div>
    <div style="clear:both;"></div>
  </div>
</div>
<footer class="mainfooter">
  <div class="pagewidth">
    <div class="left_footer">
      <h1>Blumenthals.com</h1>
	  201 N. Union St. #307<br>
	  Olean, NY 14760 US
	  <h2>716-372-4008</h2>

    </div>
    <div class="right_footer">
      <div class="copyright">
        &copy; Copyright <?php echo date("Y") ?>, <a href="#">Blumenthals.com</a>
      </div>
    </div>
    <div style="clear:both;"></div>
  </div>
</footer>
</body>
</html>
