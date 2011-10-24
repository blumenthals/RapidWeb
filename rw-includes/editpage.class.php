<?php

class EditPage extends View {
    protected $page, $rapidweb;

    public function __construct($page, $rapidweb) {
        $this->page = $page;
        $this->rapidweb = $rapidweb;
    }

    public function getScriptURL() {
        return $_SERVER['PHP_SELF'];
    }

    protected function do_head() {
        foreach($this->rapidweb->getPageTypes() as $pageType) {
            echo '<script src="'.$pageType->getEditorScript().'"></script>';
        }
    }
}
