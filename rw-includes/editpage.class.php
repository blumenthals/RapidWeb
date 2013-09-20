<?php

class EditPage extends View {

    public function getScriptURL() {
        return "{$this->rapidweb->rootURL}";
    }

    protected function do_head() {
        $this->loadJavascript('json2/json2.js');
        $this->loadJavascript('jquery-1.7.min.js');
        $this->loadJavascript('jquery-ui-1.8.16.custom.min.js');
        $this->loadJavascript('underscore/underscore.js');
        $this->loadJavascript('backbone/backbone.js');
        $this->loadJavascript('rapidweb-edit.js');
        foreach($this->rapidweb->getPageTypes() as $pageType) {
            $pageType->do_editor_head();
        }
    }

    public function render($templateFile) {
        include $templateFile;
    }

    public function the_pagetype_selector()  {
        ?>
            <label>Page Type <select name='page_type' id='page_type'>
              <?php foreach($this->rapidweb->getPageTypes() as $slug => $pageType): ?>
                <option value='<?php echo $slug ?>'<?php echo ($slug == $this->page->page_type ? ' selected' : '') ?>><?php echo $pageType->getPageTypeName() ?></option>
              <?php endforeach; ?>
            </select></label>
        <?php
    }

    public function do_foot() {
    }

    public function do_editor_settings() { ?>
        <section class='details-box'>
          <h3 class='details-box-show'>
            <img src="<?php bloginfo('template_directory'); ?>/../default/admin/arrow-down.gif" align="absmiddle"/> More Page Settings (Meta Tags, Variables, Template)
          </h3>
          <h3 class='details-box-hide'>
            <img src="<?php bloginfo('template_directory'); ?>/../default/admin/arrow-over.gif" align="absmiddle"/> Less Page Settings
          </h3>
          <div class='details'>
            <table width="100%" cellpadding="5" cellspacing="0" bgcolor="#f6f4e7">
              <tr>
                <td width="70" valign="top"><strong>Meta Description </strong> </td>
                <td><textarea name='meta' rows=2 class="txtfield"><?php echo $this->page->meta ?></textarea>                    </td>
              </tr>
              <tr>
                <td width="70" valign="top"><strong>Meta Keywords </strong> </td>
                <td><textarea name='keywords' rows=2 class="txtfield"><?php echo $this->page->keywords ?></textarea>                    </td>
              </tr>
              <tr>
                <td width="70" valign="top"><strong>Special Variables </strong> </td>
                <td><textarea name='variables' rows=2 class="txtfield"><?php echo $this->page->variables ?></textarea>  </td>
              </tr>
              <tr>
                <td width="70" valign="top"><strong>Page Template </strong> </td>
                <td><select name='template'><?php echo ListTemplates($this->page->template); ?></select>                    </td>
              </tr>
              <tr>
                <td width="70" valign="top"><strong>Don't Index This Page </strong> </td>
                <td><input type='checkbox' name='noindex' <?php echo $this->page->noindex ? 'checked' : '' ?>></td>
              </tr>
            </table>
          </div>
        </section>
    <?php }
}
