<?php
require_once('user_util.php');
$admin_username = "admin";
$error_msg = "";
if (!isset($_COOKIE['user_id']))
{
	if(isset($_POST['submit']))
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		if(!empty($username) && !empty($password))
		{
			$user_id = verifyUser($username,$password);
			if($user_id > 0 )
			{
				setcookie('user_id',$user_id);
				setcookie('username',$username);
				if($username == $admin_username)
				{
					$home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/interactive-decision-tree/admin/index.php';
					header('Location:' . $home_url);
				}
				else
				{
					$home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/interactive-decision-tree/index.php';
					header('Location:' . $home_url);
				}
			}
			else
			{
				$error_msg = 'Sorry, you must enter a valid username and password to log in.';
			}
		}
		else
		{
			$error_msg = 'Sorry, you must enter your username and password to log in.';
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Data analyser - Log In</title>
<meta charset="utf-8"/>
</head>
<body>
<?php
// If the cookie is empty, show any error message and the log-in form; otherwise confirm the log-in 
if (empty($_COOKIE['user_id'])) {
	echo '<p class="error">' . $error_msg . '</p>';
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<label for="username">Username:</label>
<input type="text" id="username" name="username"
value="<?php if (!empty($username)) echo $username; ?>" /><br /> <label for="password">Password:</label>
<input type="password" id="password" name="password" />
<input type="submit" value="登录" name="submit" />
</form>
<?php
}
else
{
	//$logout_url = "http://" . $_SERVER['HTTP_HOST'] . "/interactive-decision-tree/admin/logout.php";
	//echo('<p class="login">You are logged in as ' . $_COOKIE['username'] . '(<a href="logout.php">Logout</a>).</p>');
}
?>
</body>
</html>