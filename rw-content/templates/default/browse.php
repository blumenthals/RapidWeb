<?php if (!isset($GLOBALS['LinkStyle']) or $GLOBALS['LinkStyle'] != 'path') throw new Exception('This theme requires $LinkStyle to be "path"'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>###PAGE###</title>
<meta name="description" 	content="###META###">
<meta name="keywords" 	 	content="###METAKEYWORDS###">
<meta name="author" 		content="Mike Robertson, Blumenthals.com">
<meta name="copyright" 		content="Blumenthals.com">
<meta name="language" 		content="en-us">
<meta name="Classification" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

###METANOINDEX###
<?php $this->do_head(); ?>

<link href='http://fonts.googleapis.com/css?family=Alegreya+Sans:100,300,400,500,700,800,900' rel='stylesheet' type='text/css'>
<link href="<?php bloginfo('template_directory'); ?>/browse.css" rel="stylesheet" />
<link href="<?php bloginfo('template_directory'); ?>/color.css" rel="stylesheet" />

<!-- jQuery library (served from Google) -->
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
</head>

<body>
<header class="mainheader">
  <div class="pagewidth">
  <div class="logo"><a href="/"><img src="<?php bloginfo('template_directory'); ?>/images/logo.png"></a></div>
    <div class="mobile_menu"><a></a></div>
    <div id="main_nav">
      <nav>
        PAGECONTENT(nav)
      </nav>
      ###IF:ADMIN###
      <div class="wrapper"><a class="editsm" title="Edit Page" href="###ADMINURL###?edit=nav" style="right: 10px; top: 10px;"></a></div>
      ###ENDIF:ADMIN###
    </div>
  </div>
</header>

<?php if (strtolower($_SERVER['REQUEST_URI'])=="/home" || strtolower($_SERVER['REQUEST_URI'])=="/"): ?>
	<div class="photo_slide">
      <div class="pagewidth">
        ###IF:ADMIN###
        <div class="wrapper"><a class="editsm" title="Edit Page" href="###ADMINURL###?edit=slideshow" style="right: 10px; top: 10px;"></a></div>
        ###ENDIF:ADMIN###
        <ul class="bxslider">
    	  PAGECONTENT(slideshow, ul)
        </ul>
      </div>
    </div>
<?php endif ?>    

<div class="maincontent">
  <div class="pagewidth">
    <div class="content">
      <!--##### BODYCOPY #####-->
      <?php get_template_part('admin/toolbar'); ?>
      <?php $this->the_content(); ?>
      <!--##### END BODYCOPY #####-->
    </div>
    <aside class="sidebar">
      <div class="call_to_action">
        ###IF:ADMIN###
        <div class="wrapper"><a class="editsm" title="Edit Page" href="###ADMINURL###?edit=call_to_action" style="right: 0;top: 0px;"></a></div>
        ###ENDIF:ADMIN###
        PAGECONTENT(call_to_action)
      </div>
      <article class="top_sidebar">
        ###IF:ADMIN###
        <div class="wrapper"><a class="editsm" title="Edit Page" href="###ADMINURL###?edit=top_sidebar" style="right: 0;top: 0px;"></a></div>
        ###ENDIF:ADMIN###
        PAGECONTENT(top_sidebar)
      </article>
      <article class="middle_sidebar">
        ###IF:ADMIN###
        <div class="wrapper"><a class="editsm" title="Edit Page" href="###ADMINURL###?edit=middle_sidebar" style="right: 0;top: 0px;"></a></div>
        ###ENDIF:ADMIN###
        PAGECONTENT(middle_sidebar)
      </article>
      <article class="bottom_sidebar">
        ###IF:ADMIN###
        <div class="wrapper"><a class="editsm" title="Edit Page" href="###ADMINURL###?edit=bottom_sidebar" style="right: 0;top: 0px;"></a></div>
        ###ENDIF:ADMIN###
        PAGECONTENT(bottom_sidebar)
      </article>
    </aside>
    <div style="clear:both;"></div>
  </div>
</div>
<footer class="mainfooter">
  <div class="pagewidth">
    <div class="left_footer">
      ###IF:ADMIN###
      <div class="wrapper"><a class="editsm" title="Edit Page" href="###ADMINURL###?edit=left_footer" style="left: 0;top: 0px;"></a></div>
      ###ENDIF:ADMIN###
      PAGECONTENT(left_footer)

    </div>
    <div class="right_footer">
      <div class="wrench"><a href="rw-admin/login.php"></a></div>
      <div class="links">
        ###IF:ADMIN###
        <div class="wrapper"><a class="editsm" title="Edit Page" href="###ADMINURL###?edit=nav" style="right: 0;top: 0px;"></a></div>
        ###ENDIF:ADMIN###
        <nav>
          <ul>
            <li><a href="SiteMap">SiteMap</a></li>
            <li><a href="Search">Search</a></li>
            PAGECONTENT(nav, ul)
          </ul>
        </nav>
      </div>
      <div class="copyright">
        &copy; Copyright <?php echo date("Y") ?>, <a href="#">Blumenthals.com</a><br>
        Last Modified: ###LASTMODIFIED###
      </div>
    </div>
    <div style="clear:both;"></div>
  </div>
</footer>
<?php if (strtolower($_SERVER['REQUEST_URI'])=="/home" || strtolower($_SERVER['REQUEST_URI'])=="/"): ?>
  <link href="<?php bloginfo('template_directory'); ?>/jquery.bxslider.css" rel="stylesheet" />
  <script src="<?php bloginfo('template_directory'); ?>/jquery.bxslider.min.js"></script>
  <script>
      $(document).ready(function(){
        $('.bxslider').bxSlider({
          auto: true,
       	  randomStart: true,
    	  touchEnabled: true,
    	  speed: 1500,
    	  pause: 5000,
    	  autoHover: true,
        });
      });
   </script>
<?php endif ?>    

<script type="text/javascript">
jQuery( ".mobile_menu" ).click(function($) {
  jQuery( "#main_nav" ).slideToggle( "fast" );
});
</script>


</body>
</html>
