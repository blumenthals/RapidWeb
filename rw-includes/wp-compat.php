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

function the_content() {
	echo '###CONTENT###';
}

function bloginfo($arg) {
	global $templates, $TemplateName;
	if($arg == 'template_directory') {
		echo "rw-content/templates/$TemplateName/";
	} else if($arg == 'stylesheet_directory') {
		echo "rw-content/templates/$TemplateName/";
	} else if($arg == 'name') {
		echo RW_SITE_TITLE;
	} else {
		echo "unsupported bloginfo";
	}
}

function single_post_title($arg = '') {
	echo '###PAGE###';
}


function add_action($name, $args) {
	// Not supported yet
}

function get_template_part($slug, $name) {
	die('not implemented');
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

?>
