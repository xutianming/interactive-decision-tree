<?php
require_once('admin/login.php');
if(!empty($_COOKIE['user_id']))
{
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<link rel="stylesheet" href="styles.css"/>
<link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css"/>
<link rel="stylesheet" href="css/jquery-ui.css">
<title>Data analyser</title>
</head>
<body>
<?php

if(!isset($_COOKIE['data_file']))
{
	$_COOKIE['data_file'] = 'test.json';
}
require_once('visual_control.php');
?>
<div class="uploader">
<form action="upload_file.php" method="post" enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file" />
<br />
<input type="submit" name="submit" value="提交文件" />
</form>
</div>
</body>
</html>
<?php
}
?>