<?php

abstract class View {
    protected $page, $rapidweb;

    public function __construct($page, $rapidweb) {
        $this->page = $page;
        $this->rapidweb = $rapidweb;
    }

    public function render($templateFile) {
        throw new Exception("Implement me!");
    }

    protected function do_head() {
    }

}
