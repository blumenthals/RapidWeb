<?php 

class RapidWeb extends EventEmitter {
    private $pageTypes = array();
    private $plugins = array();
    public $globalURL;

    public function __construct() {
        $this->documentRoot = $_SERVER['DOCUMENT_ROOT'];
        if($this->documentRoot{strlen($this->documentRoot) - 1} == '/') 
            $this->documentRoot = substr($this->documentRoot, 0, strlen($this->documentRoot - 2));
        $this->registerPlugin('WikiPage');
        $this->globalURL = $this->urlForPath(dirname(__FILE__).'/../rw-global');
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

        // if this page doesn't exist yet, now's the time!
        if (! is_array($pagehash)) {
            $pagehash = array('version' => 0, 'created' => time(), 'flags' => 0);
            $newpage = 1;
        }

        $settings = RetrieveSettings();

        // set new pageinfo
        $pagehash['lastmodified'] = time();
        $pagehash['version']++;
        $pagehash['author'] = $_SERVER['REMOTE_ADDRESS'];

        if($settings['default_meta_description'] == $page->meta) {
            $pagehash['meta'] = null;
        } else {
            $pagehash['meta'] = $page->meta;
        }

        if($settings['default_title'] == $page->title) {
            $pagehash['title'] = null;
        } else {
            $pagehash['title'] = $page->title;
        }

        $pagehash['noindex'] = $page->noindex ? 1 : 0;

        if($settings['default_meta_keywords'] == $page->keywords) {
            $pagehash['keywords'] = null;
        } else {
            $pagehash['keywords'] = $page->keywords;
        }

        $pagehash['variables'] = $page->variables;

        if(!empty($page->template)) {
            $pagehash['template'] = $page->template;
        } else {
            unset($pagehash['template']);
        }

        $pagehash['gallery'] = $page->gallery;

        $pagehash['page_type'] = $page->page_type;

        if (! empty($page->content)) {
            if(is_array($page->content)) $page->content = join("\n", $page->content);
            $pagehash['content'] = preg_split('/[ \t\r]*\n/', chop($page->content));
        } else {
            $pagehash['content'] = array('');
        }

        InsertPage($dbi, $page->pagename, $pagehash);
    }
}
