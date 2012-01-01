<?php

class FileUpload {
    public $name;
    public $type;
    public $size;
    public $tmp_name;
    public $error;
    public function __construct($phpfile) {
        $this->name = $phpfile['name'];
        $this->type = $phpfile['type'];
        $this->size = $phpfile['size'];
        $this->tmp_name = $phpfile['tmp_name'];
        $this->error = $phpfile['error'];
    }

    public function moveTo($dir) {
        if(!move_uploaded_file($this->tmp_name, $dir."/".$this->name)) throw new Exception("Can't move file to $dir");
    }
}

class Request implements ArrayAccess {
    public $params = array();
    public $headers = array();
    public $root;

    public function __construct($phprequest, $phpserver, $phpfiles) {
        foreach($phprequest as $k => $v) {
            $this->params[$k] = $v;
        }
        foreach($phpfiles as $k => $v) {
            $this->params[$k] = new FileUpload($v);
        }
        foreach($phpserver as $k => $v) {
            if(preg_match('/^HTTP_(.*)/', $k, $matches)) {
                $header = join('-', array_map('ucfirst', explode('_', strtolower($matches[1]))));
                $this->headers[$header] = $v;
            }
        }

        $this->root = dirname($phpserver['SCRIPT_NAME']);
        if($this->root{strlen($this->root) - 1} != '/') $this->root .= '/';
    } 

    public function offsetExists($offset) {
        return isset($this->params[$offset]);
    }

    public function offsetGet($offset) {
        if(isset($this->params[$offset])) {
            return $this->params[$offset];
        } else {
            return NULL;
        }
    }

    public function offsetSet($offset, $value) {
        $this->params[$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($this->params[$offset]);
    }

    public function getRoot() {
        return $this->root;
    }

}

class Response {
    public $body;
    public $headers = array();
    private $jsonp = false;

    public function __construct($options = array()) {
        if($options['jsonp']) $this->jsonp = $options['jsonp'];
    }

    public function renderText($body) {
        $this->body = $body;
    }

    public function renderJSON($json) {
        if($this->jsonp) {
            $this->setHeader('Content-Type', 'application/javascript');
            $this->renderText($this->jsonp.'('.json_encode($json).')');
        } else {
            //$this->setHeader('Content-Type', 'application/json');
            $this->setHeader('Content-Type', 'text/plain');
            $this->renderText(json_encode($json));
        }
    }

    public function render($view, $data) {
        ob_start();
        extract($data);
        include(dirname(__FILE__).'/views/'.$view.'.template.php');
        $page = ob_get_contents();
        ob_end_clean();
        $this->renderText($page);
    }

    public function setHeader($header, $value) {
        $this->headers[$header] = $value;
    }

    public function addScript($name) {
        global $TemmplateName;
        $templates = array($TemplateName, 'default');
        if($f = rw_find_template($templates, array($name))) {
            echo "<script src='$f'></script>";
        } else {
            throw new Exception("Can't find script $name");
        }
    }
}

class Action {
    public $request;
    public $response;
    public $content = NULL;
    public $name;
    public $method;

    public function __construct($method, $name) {
        $this->name = $name;
        $this->method = $method;
    }

    public function getPresentation() {
        if($this->content) return $this->content;
    }
}

function rw_capture_command($command) {
    ob_start();
    rw_do_command($command);
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

function rw_do_command($command) {
    try {
        $action = new Action($_SERVER['REQUEST_METHOD'], $command);

        $action->request = new Request($_REQUEST, $_SERVER, $_FILES);

        $options = array();
        if($request['jsonp']) $options['jsonp'] = $request['jsonp'];
        $action->response = new Response($options);

        $func = 'rw_'.strtoupper($_SERVER['REQUEST_METHOD'])."_".$command;
        if(function_exists($func)) {
            call_user_func($func, $action->request, $action->response);
        } else {
            throw new Exception("Can't find handler for $command via {$_SERVER['REQUEST_METHOD']}");
        }

        foreach($action->response->headers as $k => $v) {
            header("$k: $v");
        }

        print($action->response->body);

    } catch (Exception $e) {
        header('HTTP/1.1 500 Internal Error');
        print($e->getMessage());
    }
}

function rw_POST_upload_image_ajax($request, $response) {
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
        $imgsquare = imagecreatetruecolor(150, 150);
        imagecopyresampled($imgsquare, $img, 0, 0, ($w - $s) / 2, ($h - $s) / 2, 150, 150, $s, $s);
        imagejpeg($imgsquare, $dir.'/'.$request['img']->name.".150x150.jpg");
        unset($imgsquare);

        $imagefile = $request['img']->name;
        if($w > 960 or $h > 960) {
            if($w > $h) {
                $neww = 960;
                $newh = 960 * ($h / $w);
            } else {
                $neww = 960 * ($w / $h);
                $newh = 960;
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
                    array('image' => _spaces("$vdir/$imagefile"), 'thumbnail' => _spaces($vdir.'/'.$request['img']->name.".150x150.jpg"))
                )
            )
        ));
    }
}

function rw_GET_display_gallery($request, $response) {
    global $pagehash; // @todo: refactor display.php
    $response->render('display_gallery', $pagehash);
}

function rw_GET_display_page($request, $response) {
    global $pagehash; // @todo: refactor display.php and transform.php
    // transform.php returns $html containing all the HTML markup
    include("rw-includes/transform.php");
    $response->renderText($html);
}

function _spaces($s) {
    return str_replace(' ', '%20', $s);
}
