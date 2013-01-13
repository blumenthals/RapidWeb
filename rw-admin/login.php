<?php
    require_once "../rw-includes/config.php";
    require_once "../rw-includes/stdlib.php";
    session_start();

?>
<?php if ($_SERVER['REQUEST_METHOD'] == 'POST' and checkAuth($_REQUEST['username'], $_REQUEST['password'])): 
    $_SESSION['username'] = $_REQUEST['username'];
    if ($_REQUEST['continue']) {
        header("Location: ".$_REQUEST['continue']);
    } else {
        header("Location: ..");
    }
else: ?>
    <?php echo $_SERVER['REQUEST_METHOD'] == 'POST' ? "Bad username or password" : "" ?>
    <form action='<?php echo $_SERVER['PHP_SELF'] ?>' method='post'>
        <?php if(isset($_REQUEST['continue'])): ?>
            <input name='continue' type='hidden' value='<?php echo htmlentities($_REQUEST['continue']) ?>'>
        <?php endif ?>
        <input name='username'>
        <input name='password' type='password'>
        <input type='submit'>
    </form>
<?php endif ?>
