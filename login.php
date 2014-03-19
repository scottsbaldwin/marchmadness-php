<div id="loginBody">
<?php if ($errMsg) { ?>
<div class="error"><?php print $errMsg; ?></div>
<?php } ?>
<form action="<?php print $_SERVER['PHP_SELF']; ?>" method="POST">
<span class="label">Name:</span><select name="loginPersonId">
<?php print getNameOptions($GLOBALS['loginPersonId']); ?>
</select>
<br/>
<span class="label">Birthday:</span><input type="text" name="loginBirthDay" maxlength="10" value="<?php print $GLOBALS['loginBirthDay']; ?>"> (format: <b>YYYY-MM-DD</b>, 1971-03-01 for March 1, 1971)
<br/>
<span class="label"></span><input name="login" type="submit" value="Login" class="submitBtn">
</form>
</div>
