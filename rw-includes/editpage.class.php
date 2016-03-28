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
            <img src="<?php bloginfo('template_directory'); ?>/../default/admin/plus.png" align="absmiddle" class="show_details"/> Show Page Settings (Meta Tags, Variables, Template)
          </h3>
          <h3 class='details-box-hide'>
            <img src="<?php bloginfo('template_directory'); ?>/../default/admin/minus.png" align="absmiddle" class="show_details"/> Hide Page Settings
          </h3>
          <div class='details'>
            <table width="100%">
              <tr>
                <td class="label" width="20%">Meta Description:</td>
                <td><textarea name='meta' rows=2 class="txtfield"><?php echo $this->page->meta ?></textarea>                    </td>
              </tr>
              <tr class="green_bar">
                <td class="label">Meta Keywords:</td>
                <td><textarea name='keywords' rows=2 class="txtfield"><?php echo $this->page->keywords ?></textarea>                    </td>
              </tr>
              <tr>
                <td class="label">Special Variables:</td>
                <td><textarea name='variables' rows=2 class="txtfield"><?php echo $this->page->variables ?></textarea>  </td>
              </tr>
              <tr class="green_bar">
                <td class="label">Header:</td>
                <td><textarea name='head' rows=2 class="txtfield"><?php echo $this->page->head ?></textarea>  </td>
              </tr>
              <tr>
                <td class="label">Footer:</td>
                <td><textarea name='foot' rows=2 class="txtfield"><?php echo $this->page->foot ?></textarea>  </td>
              </tr>
              <tr class="green_bar">
                <td class="label">Page Template:</td>
                <td><select name='template'><?php echo ListTemplates($this->page->template); ?></select>                    </td>
              </tr>
              <tr>
                <td class="label">Don't Index This Page:</td>
                <td><input type='checkbox' name='noindex' <?php echo $this->page->noindex ? 'checked' : '' ?>></td>
              </tr>
            </table>
          </div>
        </section>
    <?php }
}
