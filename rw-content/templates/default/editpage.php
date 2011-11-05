<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Edit: <?php echo $this->page->pagename; ?></title>
    <?php $this->do_head(); ?>
    <script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/../default/switchcontent.js"></script>
    <script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/../default/rollovers.js"></script>
    <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href="<?php bloginfo('template_directory'); ?>/../default/style.css" rel="stylesheet" type="text/css"/>
    <link rel='rapidweb-admin' href='<?php echo $this->getScriptURL() ?>'>
    <script>
        <?php /** @todo rcdata escape the json */ ?>
        var pagedata = <?php echo $this->page->toJSON(); ?>;
    </script>
  </head>
  <body bgcolor="#FFFFFF" text="#000033" link="#000066" vlink="#003399" alink="#003399" onLoad="MM_preloadImages('<?php bloginfo('template_directory'); ?>/../default/admin/upload-over.gif','<?php bloginfo('template_directory'); ?>/../default/admin/arrow-over.gif','<?php bloginfo('template_directory'); ?>/../default/admin/meta_tags-over.gif')">
    <div id='page_wrapper'>

      <table width="100%">
        <tr>
          <td width="26%" height="155" align="left" valign="middle"><a href="<?php echo $this->getScriptURL(); ?>"><img src="<?php bloginfo('template_directory'); ?>/../default/admin/logo-blue.gif" hspace="8" border=0 align="absmiddle"></a></td>
          <td height="155" colspan="2" align="left" valign="middle"><h1><span class="headertitle"> Edit <?php echo $this->page->pagename; ?></span></h1></td>
        </tr>
        <tr>
          <td height="60" width='40%' valign="middle">
            <a onClick="window.open('rw-admin/upload.php','ImageUpload',' width=551, height=494, resizable=yes')" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('upload','','<?php bloginfo('template_directory'); ?>/../default/admin/upload-over.gif',1)"><img src="<?php bloginfo('template_directory'); ?>/../default/admin/upload.gif" alt="Upload an Image from your computer" name="upload" width="102" height="49" border="0"></a>
            <a onClick="window.open('<?php echo $this->getScriptURL(); ?>?settings','Settings',' width=551, height=494, resizable=yes')" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('edit_meta_tags','','<?php bloginfo('template_directory'); ?>/../default/admin/meta_tags-over.gif',1)"><img src="<?php bloginfo('template_directory'); ?>/../default/admin/meta_tags.gif" alt="Edit Default Meta Tags" name="edit_meta_tags" width="102" height="49" border="0"></a>
          </td>
          <td valign='middle'>
            <label>Page Type <select name='page_type' id='page_type'>
              <?php foreach($this->rapidweb->getPageTypes() as $slug => $pageType): ?>
                <option value='<?php echo $slug ?>'<?php echo ($slug == $this->page->page_type ? ' selected' : '') ?>><?php echo $pageType->getPageTypeName() ?></option>
              <?php endforeach; ?>
            </select></label>
          </td> 
          <td width="25%" align="right">
            <button id='save_button'>Save</button><br>
            <input type="button" value="Cancel" onClick="history.go(-1)" name="back2">
          </td>
        </tr>
      </table>

      <br>

        <?php
            foreach($this->rapidweb->getPageTypes() as $slug => $pageType) {
                echo "<div id='{$slug}_editor' class='rapidweb-editor'>";
                $pageType->the_editor_content($this);
                echo "</div>";
            }
        ?>


    </div>

  </body>
</html>
