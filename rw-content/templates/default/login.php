<?php echo $_SERVER['REQUEST_METHOD'] == 'POST' ? "Bad username or password" : "" ?>
<form action='<?php echo $_SERVER['PHP_SELF'] ?>' method='post'>
    <?php if(isset($_REQUEST['continue'])): ?>
        <input name='continue' type='hidden' value='<?php echo htmlentities($_REQUEST['continue']) ?>'>
    <?php endif ?>
    <input name='username'>
    <input name='password' type='password'>
    <input type='submit'>
</form>
