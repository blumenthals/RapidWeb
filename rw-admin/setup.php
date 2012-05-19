<?php

echo "Compiling LESS files...";
flush();
require_once __DIR__."/../rw-global/lessphp/lessc.inc.php";

$lessc = new lessc(__DIR__."/../rw-global/less/rapidweb.less");
$out = $lessc->parse();
file_put_contents(__DIR__."/../rw-global/css/rapidweb.css", $out);

echo "Done";
