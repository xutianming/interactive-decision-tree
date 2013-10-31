<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<link rel="stylesheet" href="styles.css"/>
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
	echo '<p class="error">Sorry,no node was specified for removal.</p>';
}

if(isset($name))
{
	$flag = deleteNode($name);
	if(!$flag)
	{
		echo '<p class="error">Removal failed.</p>';
	}
	else
	{
		echo '<p>Removal succeed.</p>';
	}
}

require_once('visual_control.php');
?>
</body>
</html>