<?php

interface RWPageType {
    public function the_title();
    public function the_content($page);
    public function do_head();
    public function the_editor_content(View $view);
    public function do_editor_head();
}
