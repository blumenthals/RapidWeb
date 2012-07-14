<?php

header("Content-Type: text/plain");

echo "Compiling LESS files...";
flush();

require_once __DIR__."/../rw-global/lessphp/lessc.inc.php";

$destmtime = (int)@filemtime(__DIR__."/../rw-global/css/rapidweb.css");

$othermtime = max(array_map(function($e) { return filemtime($e); }, glob(__DIR__."/../rw-content/plugins/*/plugin.less")));

if (filemtime(__DIR__."/../rw-global/less/rapidweb.less") > $destmtime
    or filemtime(__DIR__."/../rw-global/less/plugins.less") > $destmtime
    or filemtime(__DIR__."/../rw-global/less/file-upload.less") > $destmtime
    or filemtime(__DIR__."/../rw-global/less/editor.less") > $destmtime
    or $othermtime > $destmtime) {

    echo "Compiling...";
    flush();
    $lessc = new lessc(__DIR__."/../rw-global/less/rapidweb.less");
    $out = $lessc->parse();

    file_put_contents(__DIR__."/../rw-global/css/rapidweb.css", $out);
}

echo "Done\n";
flush();
echo "Updating schema...";

flush();

require_once __DIR__."/../rw-includes/config.php";
require_once __DIR__."/../rw-includes/mysql.php";

update_modyllic(OpenDatabase());

echo "Done\n";
flush();
