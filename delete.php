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
</head>
<body>
<?php
require_once('json_util.php');
require_once('Node.class.php');

if (isset($_GET['name']))
{
	$name = $_GET['name'];
}
else
{
	echo '<p class="info">Sorry,no node was specified for removal.</p>';
}

if(isset($name))
{
	$flag = deleteNode($name);
	if(!$flag)
	{
		echo '<p class="info">Removal failed.</p>';
	}
	else
	{
		echo '<p class="info">Removal succeed.</p>';
	}
}

require_once('visual_control.php');
?>
</body>
</html>
<?php
}
?>