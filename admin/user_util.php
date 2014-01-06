<?php

function verifyUser($username, $password)
{
	$handle = fopen($_SERVER['DOCUMENT_ROOT'].'/interactive-decision-tree/admin/user.txt','r');
	$uid = 0;
	while(!feof($handle)) 
	{
		$line = fgets($handle);
		if(strlen(trim($line)) > 0)
		{
			$arr = explode("\t", $line);
			if($username == trim($arr[1]))
			{
				if(md5($password) == trim($arr[2]))
				{
					$uid = $arr[0];
					break;
				}
				else
				{
					$uid = 0;
					break;
				}	
			}
		}
	}
	fclose($handle);
	return $uid;
}

function addUser($username, $password)
{
	$handle = fopen('user.txt','r');
	$uid = 1;
	$flag = true;
	while (!feof($handle)) 
	{
		$line = fgets($handle);
		if(strlen(trim($line)) > 0)
		{
			$arr = explode("\t", $line);
			$uid = trim($arr[0]);
			if(trim($username) == trim($arr[1]))
			{
				$flag = false;
				break;
			}
		}
	}
	fclose($handle);
	if($flag)
	{
		$uid++;
		$handle = fopen('user.txt','a');
		fwrite($handle,$uid."\t".trim($username)."\t".md5(trim($password))."\n");
		fclose($handle);
	}
	return $flag;
}

// 目前只从用户列表中删除，以后增加了权限管理相关，需要同时抹去相关的权限记录
function deleteUser($userid)
{
	$handle = fopen('user.txt','r');
	$flag = true;
	$users = array();
	while(!feof($handle))
	{
		$line = fgets($handle);
		if(strlen(trim($line)) > 0)
		{
			$arr = explode("\t", $line);
			$uid = trim($arr[0]);
			if($uid == $userid)
			{
				continue;
			}
			else
			{
				array_push($users, $line);
			}
		}
	}
	fclose($handle);
	$handle = fopen('user.txt','w');
	foreach ($users as $user) {
		fwrite($handle, $user);
	}
	fclose($handle);
	return $flag;
}
?>