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
        $this->appRoot = realpath($real).'/';
        $this->globalURL = "{$url}rw-global/";
        $this->rootURL = $url;

        /* Register basic page type plugin */
        $this->registerPlugin('WikiPage');
        foreach (array(
            'rw-global/underscore',
            'rw-global/backbone',
            'rw-global/backbone-bindings',
            'rw-global/backbone.modelbinder',
            'rw-global'
            /// @todo: Bundle more.
        ) as $assetDir) {
            $this->registerBundle(new RWAssetBundle($this->appRoot."$assetDir", $this->urlForPath($assetDir)));
        }

        $this->dbc = $dbc; /// @todo: move this into this class entirely, and perform the connection here
        $this->registerResourceHandler('gallery-file-upload', '!gallery-file-upload!', array($this, 'handleGalleryUpload'));

    }

    public function handleGalleryUpload(\RapidWeb\Request $request, \RapidWeb\Response $response) {
        $file = $request['img'];
        $dir = realpath(dirname(__FILE__).'/../images/upload/').'/'.$request['pagename'];
        $vdir = $request->getRoot()."images/upload/".$request['pagename'];

        if($file->size == 0) {
            $response->renderJSON(array('error' => 'no file uploaded'));
        } else {
            if(file_exists($dir) and !is_dir($dir)) throw new Exception("$dir already exists and isn't a directory");

            if(!file_exists($dir)) {
                if(!@mkdir($dir)) throw new Exception("Can't make directory $dir");
            }

            $file->moveTo($dir);

            ini_set('memory_limit', '128M');

            /*
            $data = file_get_contents($dir.'/'.$file->name);
            $img = imagecreatefromstring($data);
            */
            $imagetype = exif_imagetype("$dir/{$file->name}");
            if($imagetype == IMAGETYPE_GIF) {
                $img = imagecreatefromgif($dir.'/'.$file->name);
            } elseif($imagetype == IMAGETYPE_JPEG) {
                $img = imagecreatefromjpeg($dir.'/'.$file->name);
            } elseif($imagetype == IMAGETYPE_PNG) {
                $img = imagecreatefrompng($dir.'/'.$file->name);
            } else {
                print(json_encode(array('$error' => "Unknown file type")));
                exit();
            }

            $w = imagesx($img);
            $h = imagesy($img);
            $s = min($w, $h);
            foreach(array(300, 175, 150, 75) as $size) {
                $imgsquare = imagecreatetruecolor($size, $size);
                imagecopyresampled($imgsquare, $img, 0, 0, ($w - $s) / 2, ($h - $s) / 2, $size, $size, $s, $s);
                imagejpeg($imgsquare, $dir.'/'.$request['img']->name.".{$size}x${size}.jpg");
                unset($imgsquare);
            }

            $imagefile = $request['img']->name;
            if($w > 1250 or $h > 1250) {
                if($w > $h) {
                    $neww = 1250;
                    $newh = 1250 * ($h / $w);
                } else {
                    $neww = 1250 * ($w / $h);
                    $newh = 1250;
                }
                $newi = imagecreatetruecolor($neww, $newh);
                imagecopyresampled($newi, $img, 0, 0, 0, 0, $neww, $newh, $w, $h); 
                if(!preg_match('/[.]jpg$/', $imagefile)) {
                    $imagefile .= '.jpg';
                }
                imagejpeg($newi, "$dir/$imagefile");
            }

            $response->renderJSON(array(
                '$insertAll' => array(
                    'gallery' => array(
                        array('image' => $this->_spaces("$vdir/$imagefile"), 'thumbnail' => $this->_spaces($vdir.'/'.$request['img']->name.".150x150.jpg"))
                    )
                )
            ));

            return $response;
        }
    }

    private function _spaces($s) {
        return str_replace(' ', '%20', $s);
    }

    public function initialize() {
        if ($_COOKIE[session_name()]) {
            session_start();
        }
        $this->trigger('init');
        $this->trigger('rw-init', $this);
    }

    public function registerPagetype($slug, $handler) {
        $this->pageTypes[$slug] = $handler;
    }

    public function registerResourceHandler($name, $route, $handler) {
        $routeObject = new ArrayObject(array('route' => $route, 'handler' => $handler, 'url' => $this->rootURL.$name), ArrayObject::ARRAY_AS_PROPS | ArrayObject::STD_PROP_LIST);
        $this->endpoints[$name] = $routeObject;
        return $routeObject->url;
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
        $this->plugins[$pluginClass] = new $pluginClass($this);
    }

    public function getPlugin($pluginClass) {
        return $this->plugins[$pluginClass];
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
        $pagehash = RetrievePage($dbc, $page->pagename);

        // if this page doesn't exist yet, now's the time!
        if (! is_array($pagehash)) {
            $pagehash = array('version' => 0, 'created' => time(), 'flags' => 0);
            $newpage = 1;
        }

        if (!$pagehash['page_type']) $pagehash['page_type'] = 'page';

        $old = new \RapidWebPage($pagehash);

        $new = new \RapidWebPage((array)$page);
        foreach ($page as $k => $v) {
            $new->$k = $v;
        }

        $handler = $this->pageTypes[$new->page_type];

        $handler->savePage($new, $old);

        header('Content-Type: text/json');

        print(json_encode(
            array(
                'location' => $this->urlForPage($page->pagename)
            )
        ));
    }

    public function urlForPage($pagename) {
        global $LinkStyle;
        if (isset($LinkStyle) and $LinkStyle == 'path') { 
            return $this->rootURL . $pagename;
        } else {
            return $this->rootURL . 'index.php?' . $pagename;
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

            $response->send();

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
            $potentialResourceHandler = substr($_SERVER['REQUEST_URI'], strlen($this->rootURL));
            foreach ($this->endpoints as $name => $route) {
                if (preg_match($route->route, $potentialResourceHandler, $matches)) {
                    $request = new RapidWeb\Request($_REQUEST, $_SERVER, $_FILES);

                    $options = array();
                    foreach ($matches as $key => $value) {
                        $request[$key] = $value;
                    }
                    if($request['jsonp']) $options['jsonp'] = $request['jsonp'];
                    $response = new RapidWeb\Response($options);
                    return call_user_func($route->handler, $request, $response);
                }
            }
        }
        return false;
    }

    public function getRouteNamed($name) {
        return $this->endpoints[$name];
    }

    protected $loadedScripts = array();

    public function loadJavascript($url) {
        if (!in_array($url, $this->loadedScripts)) {
            $this->loadedScripts[] = $url;
            echo "<script src='$url'></script>";
        }
    }

    public function assetURL($asset) {
        assert('$asset');
        foreach ($this->bundles as $bundle) {
            if ($bundle->hasAsset($asset)) return $bundle->assetURL($asset);
        }
        throw new Exception("Can't find script '$asset'");
    }

    public function isAuthenticated() {
        if (!session_id()) return false;

        return !!$_SESSION['username'];
    }

    public function mustAuthenticate() {
        if ($this->isAuthenticated()) {
            return true;
        } else {
            header("Location: ".$this->rootURL."rw-admin/login.php?continue=".$_SERVER['REQUEST_URI']);
            exit();
        }
    }

    public function deAuthenticate() {
        if ($_SESSION['username']) {
            $_SESSION['username'] = null;
        }
    }

}
