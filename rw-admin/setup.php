<?php

/// FIXME
/*
echo "Compiling LESS files...";
flush();
require_once __DIR__."/../rw-global/lessphp/lessc.inc.php";

$lessc = new lessc(__DIR__."/../rw-global/less/rapidweb.less");
$out = $lessc->parse();
file_put_contents(__DIR__."/../rw-global/css/rapidweb.css", $out);

echo "Done\n";
flush();
 */
echo "Updating schema...";

flush();

require_once __DIR__."/../rw-includes/config.php";
require_once __DIR__."/../rw-includes/mysql.php";

update_modyllic(OpenDatabase());

echo "Done\n";
flush();
