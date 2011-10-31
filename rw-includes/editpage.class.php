<?php

class EditPage extends View {

    public function getScriptURL() {
        return $_SERVER['PHP_SELF'];
    }

    protected function do_head() {
        foreach($this->rapidweb->getPageTypes() as $pageType) {
            $pageType->do_editor_head();
        }
    }

    public function render($templateFile) {
        include $templateFile;
    }
}
