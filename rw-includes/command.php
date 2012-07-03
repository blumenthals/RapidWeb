<?php

// @todo refactor remove completely
// @todo refactor into its own handler class
function rw_POST_upload_image_ajax($request, $response) {
    global $RapidWeb;
    $route = $RapidWeb->getRouteNamed('gallery-file-upload');
    return call_user_func($route->handler, $request, $response);
}

// @todo refactor into the gallery plugin
function rw_GET_display_gallery($request, $response) {
    global $pagehash; // @todo: refactor display.php
    $response->render('display_gallery', $pagehash);
}
