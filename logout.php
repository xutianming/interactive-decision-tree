<?php
if(isset($_COOKIE['user_id']))
{
	setcookie('user_id','',time()-3600,$_SERVER['HTTP_HOST']);
	setcookie('username','',time()-3600,$_SERVER['HTTP_HOST']);
}
$home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/interactive-decision-tree/admin';
header('Location:' . $home_url);
?>