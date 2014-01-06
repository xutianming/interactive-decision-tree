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
<link rel="stylesheet" href="../css/jquery-ui.css"/>
<title>Data analyser</title>
</head>
<body>
<a href='adduser.php'>增加用户</a>
<table>
<tr><th>用户ID</th><th>用户名</th><th>操作</th></tr>
<?php
$handle = fopen('user.txt','r');
while(!feof($handle)) 
{
	$line = fgets($handle);
	if(strlen(trim($line)) > 0)
	{
		$arr = explode("\t", trim($line));
		echo "<tr><td>".$arr[0]."</td><td>".$arr[1]."</td><td>".
			 "<a href='deleteuser.php?id=".$arr[0]."'>删除</a>"."</td></tr>";
	}
}
fclose($handle);
?>
</table>
</body>
</html>
<?php
}
?>