<?php

require_once __DIR__."/../rw-global/lessphp/lessc.inc.php";

lessc::ccompile(__DIR__."/../rw-global/less/rapidweb.less", __DIR__."/../rw-global/css/rapidweb.css");
