<?php

class EditPage extends View {
    protected $page;

    public function __construct($page) {
        $this->page = $page;
    }

    public function getScriptURL() {
        return $_SERVER['PHP_SELF'];
    }

    protected function do_head() {
    }
}
