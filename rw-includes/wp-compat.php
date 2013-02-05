<?php

$rw_have_posts = true;

function have_posts() {
	global $rw_have_posts;
	return $rw_have_posts;
}

function the_post() {
	global $rw_have_posts;
	$rw_have_posts = 0;
}

function is_admin() {
    global $RapidWeb;
    return $RapidWeb->isAuthenticated();
}

function the_content() {
	echo '###CONTENT###';
}

function get_bloginfo($arg) {
	global $templates, $TemplateName;
	$myroot = dirname($_SERVER['SCRIPT_NAME']);
    if($myroot == '/') $myroot = '';
	$myroot = preg_replace('#^(.*)/rw-admin$#', '\1', $myroot);
	if($arg == 'template_directory') {
		return $myroot."/rw-content/templates/$TemplateName";
	} else if($arg == 'stylesheet_directory') {
		return $myroot."/rw-content/templates/$TemplateName";
	} else if($arg == 'name') {
		return RW_SITE_TITLE;
	} else {
		return "unsupported bloginfo";
	}
}

function bloginfo($arg) {
    echo get_bloginfo($arg);
}

function single_post_title($arg = '') {
	echo '###PAGE###';
}


function add_action($name) {
    global $RapidWeb;
    return call_user_func_array(array($RapidWeb, 'on'), func_get_args());
}

function get_template_part($slug, $name = false) {
	global $TemmplateName;
	$try = array();
	$templates = array($TemplateName, 'default');
	
	if($name) {
		array_push($try, $slug."-".$name.".php");
	}
	array_push($try, $slug.".php");
	if($template = rw_find_template($templates, $try)) {
		require($template);
	} else  {
		die("Could not load template part");
	}
}

function rw_find_template($templates, $names) {
	foreach($templates as $template) {
		foreach($names as $name ) {
			if(file_exists($f = 'rw-content/templates/'.$template.'/'.$name)) {
				return $f;
			}
		}
	}
	return false;
}

function wp_nav_menu($args) {
	if($args['container_class']) $class=" class='{$args['container_class']}'";
	if($args['container_id']) $id=" id='{$args['container_id']}'";
	echo "<div$class$id>";
	$doc = new DOMDocument();
	if(!$doc->loadHTML($p= _pagecontent($args['theme_location'].'-navigation'))) die("Error parsing");
	$uls = $doc->getElementsByTagName('ul');
	$theUL = $uls->item(0);
	if(is_object($theUL)) {
		$theUL->setAttribute('class', 'menu');
		$uls = $theUL->getElementsByTagName('ul');
		for($i = 0; $i < $uls->length; $i++) {
			$uls->item($i)->setAttribute('class', 'sub-menu');
		} 
		$lis = $theUL->getElementsByTagName('li');
		for($i = 0; $i < $lis->length; $i++) {
			$lis->item($i)->setAttribute('class', 'menu-item');
		} 
		echo $doc->saveXML($theUL);
	}
	?>
	</div>
<?php }

function dynamic_sidebar($n) {
	global $RW_SIDEBARS;
	if(isset($RW_SIDEBARS) && $RW_SIDEBARS[$n]) {
		call_user_func($RW_SIDEBARS[$n]);
		return true;
	}
}

if(!function_exists('wp_footer')) {
function wp_footer() {
}
}

function wp_head() {
}

function do_action($action) {
    global $RapidWeb;
    return call_user_func_array(array($RapidWeb, 'trigger'), func_get_args());
}

function get_search_form() { ?>
	<form role='search' action='<?php echo $_SERVER['PHP_SELF']; ?>' method='get' id="searchform">
		<div><label class="screen-reader-text" for="s">Search for:</label>
			<input type="text" value="" name="s" id="s" />
			<input type='hidden' name='searchtype' value='full' />
			<input type="submit" id="searchsubmit" value="Search" />
		</div>
	</form>     
<?php }


define('ABSPATH', realpath(dirname(__FILE__)."/../"));
