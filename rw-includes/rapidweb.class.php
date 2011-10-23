<?php 

class RapidWeb extends EventEmitter {
    private $pageTypes = array();

    public function __construct() {
    }

    public function initialize() {
        $this->trigger('init');
        $this->trigger('rw-init', $this);
    }

    public function register_pagetype($slug, $handler) {
        $this->pageTypes[$slug] = $handler;
    }

    function add_plugins_directory($directory) {
        $plugins = glob($directory.'/*/plugin.php');
        if($plugins) {
            foreach($plugins as $plugin) {
                set_include_path(get_include_path().PATH_SEPARATOR.dirname($plugin));
            }
        }
    }

    function load_plugins($plugins) {
        foreach($plugins as $plugin) call_user_func(array($plugin, 'initialize'), $this);
    }
}
