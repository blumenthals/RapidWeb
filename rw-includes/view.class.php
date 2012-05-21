<?php

abstract class View {
    protected $rapidweb;
    public $page;

    public function __construct($page, $rapidweb) {
        $this->page = $page;
        $this->rapidweb = $rapidweb;
    }

    public function render($templateFile) {
        throw new Exception("Implement me!");
    }

    protected function do_head() {
    }

    public function loadJavascript($script) {
        $this->rapidweb->loadJavascript($this->rapidweb->assetURL($script));
    }

}
