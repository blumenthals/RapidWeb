<?php

class EditPage extends View {

    public function getScriptURL() {
        return "{$this->rapidweb->rootURL}admin.php";
    }

    protected function do_head() {
        $this->loadJavascript('json2/json2.js');
        $this->loadJavascript('jquery-1.7.min.js');
        $this->loadJavascript('jquery-ui-1.8.16.custom.min.js');
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
}
