<!doctype html>
<html>
<head>
<title><?php $this->the_title(); ?></title>
<?php $this->do_head(); ?>
<style>
body { font-size: 12px; }
* { margin: 0; padding: 0 }
</style>
</head>
<body>
<h1><?php $this->the_title(); ?></h1>
<?php get_template_part('admin/toolbar'); ?>
<?php $this->the_content(); ?>
</body>
</html>
