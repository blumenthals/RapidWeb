<?php 

/* Requires for things that should be available site-wide
 */
require_once dirname(__FILE__)."/php-selector/selector.inc";

class RapidWeb extends EventEmitter {
    private $pageTypes = array();
    private $endpoints = array();
    private $plugins = array();
    public $globalURL;
    public $dbc;

    public function __construct() {
        global $dbc;
        /* Find the root of the rapidweb installation */
        $real = dirname($_SERVER['SCRIPT_FILENAME']);
        while($real and !file_exists("$real/rw-config.php")) $real = dirname($real);
        if(!$real) throw new Exception("Can't find our root directory");

        /* Find the common suffix between the script filename and the script name directories. */
        $url = dirname($_SERVER['SCRIPT_NAME']);
        $reala = explode('/', $real);
        $urla = explode('/', $url);
        $parts = array();
        while(array_pop($reala) == ($part = array_pop($urla))) array_unshift($parts, $part);
        array_unshift($parts, $part);
        $url = join('/', $parts);
        if ($url{0} != '/') $url = '/'.$url;
        if ($real{0} != '/') $real = '/'.$real;
        if ($url{strlen($url) - 1} != '/') $url .= '/';
        if ($real{strlen($real) - 1} != '/') $real .= '/';

        /* Set up variables */
        $this->appRoot = realpath($real);
        $this->globalURL = "{$url}rw-global/";
        $this->rootURL = $url;

        /* Register basic page type plugin */
        $this->registerPlugin('WikiPage');
        foreach (array(
            'rw-global/backbone',
            'rw-global/backbone.modelbinder',
            'rw-global'
        ) as $assetDir) {
            $this->registerBundle(new RWAssetBundle($this->appRoot."/$assetDir", $this->urlForPath($assetDir)));
        }

        $this->dbc = $dbc; // @todo: move this into this class entirely, and perform the connection here

    }

    public function initialize() {
        $this->trigger('init');
        $this->trigger('rw-init', $this);
    }

    public function registerPagetype($slug, $handler) {
        $this->pageTypes[$slug] = $handler;
    }

    public function registerEndpoint($name, $handler) {
        $this->endpoints[$name] = $handler;
        return $this->rootURL.$name;
    }

    public function add_plugins_directory($directory) {
        $plugins = glob(realpath($directory).'/*/plugin.php');
        if ($plugins) {
            foreach($plugins as $plugin) {
                $last = end($this->plugins);
                include $plugin;
                $current = end($this->plugins);
                $url = $this->urlForPath(dirname($plugin));
                if($last != $current) $current->setBaseDir(dirname($plugin));
            }
        }
    }

    public function urlForPath($path) {
        $path = realpath($path);
        if (strpos($path, $this->appRoot) === 0) {
            $url = substr_replace($path, $this->rootURL, 0, strlen($this->appRoot));
            if($url{strlen($url) - 1} != '/') $url .= '/'; 
            return $url;
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

    public function registerBundle(RWBundle $bundle) {
        $this->bundles[] = $bundle;
    }

    public function dispatchCommand($request) {
        $method = 'do_'.$request->command;
        // @todo this is hacky, but the page is what the initial command needs. Refactor.
        $this->$method($request->page);
    }

    public function do_savePage($page) {
        global $dbc;
        global $LinkStyle;
        $pagehash = RetrievePage($dbc, $page->pagename);

        // if this page doesn't exist yet, now's the time!
        if (! is_array($pagehash)) {
            $pagehash = array('version' => 0, 'created' => time(), 'flags' => 0);
            $newpage = 1;
        }

        $settings = RetrieveSettings($dbc);

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

        $pagehash['plugins'] = $page->plugins;

        $pagehash['page_type'] = $page->page_type;

        if (! empty($page->content)) {
            if(is_array($page->content)) $page->content = join("\n", $page->content);
            $pagehash['content'] = preg_split('/[ \t\r]*\n/', chop($page->content));
        } else {
            $pagehash['content'] = array('');
        }

        InsertPage($dbc, $page->pagename, $pagehash);

        header('Content-Type: text/json');

        if (isset($LinkStyle) and $LinkStyle == 'path') {
            print(json_encode(
                array(
                    'page' => array(
                        'public' => $this->rootURL . $page->pagename,
                        'private' => $this->rootURL . 'admin.php/' . $page->pagename
                    )
                )
            ));
        } else {
            print(json_encode(
                array(
                    'page' => array(
                        'public' => $this->rootURL . 'index.php?' . $page->pagename,
                        'private' => $this->rootURL . 'admin.php?' . $page->pagename
                    )
                )
            ));
        }
    }

    function linkExistingWikiWord($wikiword, $linktext='', $target = '') {
        global $LinkStyle;
        global $ScriptUrl;
        $enc_word = rawurlencode($wikiword);
        if(empty($linktext)) $linktext = htmlspecialchars($wikiword);
        if($target) $dtarget = " target='$target'";
        if (isset($LinkStyle) and $LinkStyle == 'path') {
            if (strpos($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']) === 0) {
                return "<a href='$ScriptUrl/$enc_word'$dtarget>$linktext</a>";
            } else {
                return "<a href='{$this->rootURL}$enc_word'$dtarget>$linktext</a>";
            }
        } else {
            return "<a href='$ScriptUrl?$enc_word'$dtarget>$linktext</a>";
        }
    }

    public function dispatch($command) {
        try {
            if ($command instanceof RapidWeb\Action) {
                $action = $command;
            } else {
                $action = new RapidWeb\CommandAction($_SERVER['REQUEST_METHOD'], $command);
            }

            $request = new RapidWeb\Request($_REQUEST, $_SERVER, $_FILES);

            $options = array();
            if($request['jsonp']) $options['jsonp'] = $request['jsonp'];
            $response = new RapidWeb\Response($options);

            $action->execute($request, $response);

            foreach($response->headers as $k => $v) {
                header("$k: $v");
            }

            print($response->body);

        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Error');
            print($e->getMessage());
        }
    }

    function capture($command) {
        ob_start();
        $this->dispatch($command);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    function route() {
        if (strpos($_SERVER['REQUEST_URI'], $this->rootURL) == 0) {
            $potentialEndpoint = substr($_SERVER['REQUEST_URI'], strlen($this->rootURL));
            if (isset($this->endpoints[$potentialEndpoint])) {
                return call_user_func($this->endpoints[$potentialEndpoint]);
            }
        }
        return false;
    }

    public function loadJavascript($script) {
        $found = false;
        foreach ($this->bundles as $bundle) {
            if ($bundle->hasAsset($script)) {
                $bundle->loadJavascript($script);
                $found = true;
                break;
            }
        }
        if (!$found) throw new Exception("Can't find script '$script'");
    }

}
