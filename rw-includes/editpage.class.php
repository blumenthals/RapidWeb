<?php

class EditPage extends View {

    public function getScriptURL() {
        return $_SERVER['PHP_SELF'];
    }

    protected function do_head() {
        echo "<script src='{$this->rapidweb->globalURL}/jquery-1.6.4.min.js'></script>";
        echo "<script src='{$this->rapidweb->globalURL}/jquery-ui-1.8.16.custom.min.js'></script>";
        echo "<script src='{$this->rapidweb->globalURL}/rapidweb-edit.js'></script>";
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
