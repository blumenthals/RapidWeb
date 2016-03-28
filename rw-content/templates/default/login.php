<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Rapidweb LogiRapidweb Loginn</title>
<meta name="author" 		content="Mike Robertson, Blumenthals.com">
<meta name="copyright" 		content="Blumenthals.com">
<meta name="language" 		content="en-us">
<meta name="Classification" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="/rw-content/templates/default/admin.css" rel="stylesheet" type="text/css">
<link href="/rw-content/templates/default/color.css" rel="stylesheet" type="text/css">
<link href='/rw-content/templates/default/images/favicon.png' rel="shortcut icon" type="image/png" >
<?php $this->do_head(); ?>
</head>
<body class='rw-login'>
<div class="login_background">
  <div class="logo_banner">
    <div class="logo"><img src="/rw-content/templates/default/images/logo.png"></div>
  </div>
  <div class='rw-login-form'>
    <?php echo $_SERVER['REQUEST_METHOD'] == 'POST' ? "Bad username or password" : "" ?>
    <form action='<?php echo $_SERVER['PHP_SELF'] ?>' method='post'>
      <?php if(isset($_REQUEST['continue'])): ?>
        <input name='continue' type='hidden' value='<?php echo htmlentities($_REQUEST['continue']) ?>'>
      <?php endif ?>
        <label for='username'>Username:</label>
        <input name='username' type='text' id='username'>
        <label for='password'>Password:</label>
        <input name='password' type='password' id='password'>
      <button class="login" type='submit' value="login">
    </form>
  </div>
</div>
<div class="login_copyright">
  &copy; Copyright <?php echo date("Y") ?>, <a href="#">Blumenthals.com</a> All rights reserved.<br>Powered by <a href="http://www.RapidWeb.info">RapidWeb</a>
</div>
</body>
</html>
