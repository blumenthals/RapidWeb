<?php

abstract class RWPlugin extends RWBundle {
    protected $rapidweb;
    protected $baseURL;
    private $baseDir;

    public function __construct(RapidWeb $rapidweb) {
        $this->rapidweb = $rapidweb;
    }

    public function setBaseDir($dir) {
        if ($dir{strlen($dir) - 1}  != '/') $dir .= '/';
        $this->baseDir = $dir;
        $this->baseURL = $this->rapidweb->urlForPath($dir);
    }

    public function assetDir() {
        return $this->baseDir;
    }

    public function assetURL($asset) {
        if ($this->hasAsset($asset)) {
            return $this->baseURL . $asset;
        } else {
            return $this->rapidweb->assetURL($asset);
        }
    }

    public static function initialize($rapidweb) {
        new static($rapidweb);
    }

    public function loadJavascript($script) {
        $this->rapidweb->loadJavascript($this->assetURL($script));
    }

    public function content_for_page($pagename) {
        global $dbc;
        $page = new RapidWebPage(RetrievePage($dbc, $pagename));
        return $this->the_content($page);
    }

    public function savePage(\Rapidweb\Page $new, \Rapidweb\Page $old) {
	global $dbc;
        $settings = RetrieveSettings($dbc);

        // set new pageinfo
        $new->lastmodified = time();
        $new->version = $old->version + 1;
        $new->author = $_SERVER['REMOTE_ADDRESS'];

        if($settings['default_meta_description'] == $new->meta) $new->meta = null;

        if($settings['default_title'] == $new->title) $new->title = null;

        $new->noindex = (int)$new->noindex;

        if($settings['default_meta_keywords'] == $new->keywords) $new->keywords = null;

        if(empty($new->template)) {
            unset($new->template);
        }

        if (! empty($new->content)) {
            if(is_array($new->content)) $new->content = join("\n", $new->content);
            $new->content = preg_split('/[ \t\r]*\n/', chop($new->content));
        } else {
            $new->content = array('');
        }

        InsertPage($dbc, $page->pagename, (array)$new);
    }
}
