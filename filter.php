<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<link rel = "stylesheet" href="styles.css"/>
</head>
<body>
<?php
$flag = $_POST['radio_show'];
if($flag == "false") // 显示部分
{
	filterNode();
	$data_file = "tmp.json";
}
require_once("json_util.php");
require_once("Node.class.php");
require_once('visual_control.php');
?>
</body>
</html>