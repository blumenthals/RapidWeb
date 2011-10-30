<?php

class EditPage extends View {

    public function getScriptURL() {
        return $_SERVER['PHP_SELF'];
    }

    protected function do_head() {
        foreach($this->rapidweb->getPageTypes() as $pageType) {
            if($pageType->getEditorScript()) {
                echo '<script src="'.$pageType->getEditorScript().'"></script>';
            }
        }
    }

    public function render($templateFile) {
        include $templateFile;
    }
}
