<?php
require_once('login.php');
if(!empty($_COOKIE['user_id']) && $_COOKIE['username']== "admin")
{
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<link rel="stylesheet" href="../styles.css"/>
<link rel="stylesheet" href="../css/jquery-ui-1.10.3.custom.min.css"/>
<link rel="stylesheet" href="../css/jquery-ui.css">
<title>Data analyser admin</title>
</head>
<body>
<h2>What do you want to do?</h2>
<ul>
	<li><a href="usermanagement.php">用户管理</a></li>
	<li><a href="rightmanagement.php">分配权限</a></li>
</ul>
</body>
</html>
<?php
}
?>