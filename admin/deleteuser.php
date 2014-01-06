<?php
require_once('user_util.php');
if (isset($_GET['id']))
{
	$uid = $_GET['id'];
}
else
{
	echo '<p class="info">Sorry,no user was specified for removal.</p>';
}

if(isset($uid))
{
	$flag = deleteUser($uid);
	if(!$flag)
	{
		echo '<p class="info">Removal failed.</p>';
	}
	else
	{
		echo '<p class="info">Removal succeed.</p>';
	}
}

//$home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/interactive-decision-tree/admin/usermanagement.php';
//header('Location:' . $home_url);
?>