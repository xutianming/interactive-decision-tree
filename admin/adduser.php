<?php
require_once('user_util.php');
$output_form = true;
if(isset($_POST['submit']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	$pwdconfirm = $_POST['pwdconfirm'];
	if(!empty($username) && !empty($password) && !empty($pwdconfirm) && ($password ==$pwdconfirm))
	{
		$flag = addUser($username,$password);
		if($flag)
		{
			$home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/interactive-decision-tree/admin/usermanagement.php';
			header('Location:' . $home_url);
		}
		else
		{
			$output_form = true;
		}
	}
}
if($output_form)
{
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
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<label for="username">username:</label><br/>
<input id="username" name="username" type="text" size="30"/><br/>
<label for="password">password:</label><br/>
<input id="password" name="password" type="text" size="30"/><br/>
<label for="pwdconfirm">password confirm:</label></br>
<input id="pwdconfirm" name="pwdconfirm" type="text" size="30" /><br/>
<input type="submit" name="submit" value="Submit"/>
</form>
</body>
</html>
<?php
}
}
?>