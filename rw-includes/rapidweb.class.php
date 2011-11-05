<?php 

class RapidWeb extends EventEmitter {
    private $pageTypes = array();
    private $plugins = array();

    public function __construct() {
        $this->documentRoot = $_SERVER['DOCUMENT_ROOT'];
        if($this->documentRoot{strlen($this->documentRoot) - 1} == '/') 
            $this->documentRoot = substr($this->documentRoot, 0, strlen($this->documentRoot - 2));
        $this->registerPlugin('WikiPage');
    }

    public function initialize() {
        $this->trigger('init');
        $this->trigger('rw-init', $this);
    }

    public function register_pagetype($slug, $handler) {
        $this->pageTypes[$slug] = $handler;
    }

    public function add_plugins_directory($directory) {
        $plugins = glob($directory.'/*/plugin.php');
        if($plugins) {
            foreach($plugins as $plugin) {
                $last = end($this->plugins);
                include $plugin;
                $current = end($this->plugins);
                if($last != $current) $current->setBaseURL($this->urlForPath(dirname($plugin).'/'));
            }
        }
    }

    public function urlForPath($path) {
        if(strpos($path, $this->documentRoot) === 0) {
            return substr($path, strlen($this->documentRoot));
        } else {
            return null;
        }
    }

    public function getPageTypes() {
        return $this->pageTypes;
    }

    public function getPageType($type) {
        return $this->pageTypes[$type];
    }

    public function registerPlugin($pluginClass) {
        $this->plugins[] = new $pluginClass($this);
    }

    public function dispatchCommand($request) {
        $method = 'do_'.$request->command;
        // @todo this is hacky, but the page is what the initial command needs. Refactor.
        $this->$method($request->page);
    }

    public function do_savePage($page) {
        global $dbi;
        $pagehash = RetrievePage($dbi, $page->pagename);
    }

}
