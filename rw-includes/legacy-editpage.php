<?php

require __DIR__."/../rw-admin/require-authentication.php";


// editpage relies on $pagename and $ScriptUrl

if ($edit) {
    $pagename = rawurldecode($edit);
    if (get_magic_quotes_gpc()) $pagename = stripslashes($pagename);
    $banner = htmlspecialchars($pagename);
    $pagehash = RetrievePage($dbc, $pagename);
} else {
    ExitWiki("No page name passed into editpage!");
}

if (is_array($pagehash)) {

    if ($pagehash["version"] > 1) {
        if(IsInArchive($dbc, $pagename))
            $pagehash["copy"] = 1;
    }

} else {
    $textarea = sprintf("Create %s here.", htmlspecialchars($pagename));
    unset($pagehash);
    $pagehash["version"] = 0;
    $pagehash["lastmodified"] = time();
    $pagehash["author"] = '';
}

GeneratePage('EDITPAGE', htmlspecialchars($pagehash['content']), $pagename, $pagehash);   
