<?php

// @todo refactor remove completely
function rw_capture_command($command) {
    ob_start();
    rw_do_command($command);
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

// @todo refactor into rapidweb class
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

// @todo refactor into its own handler class
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

// @todo refactor into the gallery plugin
function rw_GET_display_gallery($request, $response) {
    global $pagehash; // @todo: refactor display.php
    $response->render('display_gallery', $pagehash);
}

// @todo refactor into the wikipage plugin
function rw_GET_display_page($request, $response) {
    global $pagehash; // @todo: refactor display.php and transform.php
    // transform.php returns $html containing all the HTML markup
    include("rw-includes/transform.php");
    $response->renderText($html);
}

// @todo refactor into a namespace of some sort
function _spaces($s) {
    return str_replace(' ', '%20', $s);
}
