<?php

require_once("Node.class.php");
define("TMPFILE","tmp.json");


// 删除不符合条件的节点，把剩余节点输出到tmp文件中
function filterNode()
{
	if(!isset($_COOKIE['data_file']))
	{
		$data_file = "test.json";
	}
	else
	{
		$data_file = $_COOKIE['data_file'];
	}
	$json_str = file_get_contents($data_file);
	$json_obj = json_decode($json_str);
	$node_queue = array();
	array_push($node_queue,&$json_obj);
	if($json_obj->size <= 3000) // 定义筛选条件为size大于3000的显示
	{
		unset($json_obj);
		$json_str_new = json_encode($json_obj);
		file_put_contents(TMPFILE,$json_str_new);
		return true;
	}
	while(count($node_queue) > 0)
	{
		if(count($node_queue[0]->children) > 0)
		{
			$i = 0;
			while($i<count($node_queue[0]->children)) 
			{
				if($node_queue[0]->children[$i]->size <=3000)
				{
					array_splice($node_queue[0]->children,$i,1);
				}
				else
				{
					$i++;
				}
			}
			for($i=0;$i<count($node_queue[0]->children);$i++)
			{
				if(count($node_queue[0]->children[$i]->children)>0)
				{
					array_push($node_queue,&$node_queue[0]->children[$i]);
				}
			}
		}
		array_shift($node_queue);
	}
	$json_str_new = json_encode($json_obj);
	file_put_contents(TMPFILE, $json_str_new);
	return true;
}

function deleteNode($name)
{
	if(!isset($_COOKIE['data_file']))
	{
		$data_file = "test.json";
	}
	else
	{
		$data_file = $_COOKIE['data_file'];
	}
	$json_str = file_get_contents($data_file);
	$json_obj = json_decode($json_str);
	$node_queue = array();
	array_push($node_queue, &$json_obj);
	if($json_obj->name == $name)
	{
		unset($json_obj);
		$keys_file = $data_file."keys";
		if(file_exists($keys_file))
		{
			$handle = fopen($keys_file, 'w');
			fwrite($handle,"");
			fclose($handle);
		}
		$json_str_new = json_encode($json_obj);
		file_put_contents($data_file, $json_str_new);
		return true;
	}
	while(count($node_queue) > 0)
	{
		if(count($node_queue[0]->children) > 0)
		{
			for ($i=0;$i<count($node_queue[0]->children);$i++) 
			{
				if($node_queue[0]->children[$i]->name == $name)
				{
					$node_list = getSubtreeNodes(&$node_queue[0]->children[$i]);
					deleteKeyName($node_list);
					array_splice($node_queue[0]->children,$i,1);
					$json_str_new = json_encode($json_obj);
					file_put_contents($data_file, $json_str_new);
					return true;
				}
				if(count($node_queue[0]->children[$i]->children)>0)
				{
					array_push($node_queue,&$node_queue[0]->children[$i]);
				}
			}
		}
		array_shift($node_queue);
	}
	return false;
}

function updateNode($origin_name,$node)
{
	if(!isset($_COOKIE['data_file']))
	{
		$data_file = "test.json";
	}
	else
	{
		$data_file = $_COOKIE['data_file'];
	}
	$has_dup_key = checkDupName($node['name']);
	if($has_dup_key)
		return false;
	$json_str = file_get_contents($data_file);
	$json_obj = json_decode($json_str);
	$node_queue = array();
	array_push($node_queue, &$json_obj);
	if($json_obj->name == $origin_name)
	{
		if(count($json_obj->children) > 0)
			$node["children"] = $json_obj->children;
		$json_str_new = json_encode($node);
		file_put_contents($data_file, $json_str_new);
		return true;
	}
	while(count($node_queue) > 0)
	{
		if(count($node_queue[0]->children) > 0)
		{
			for ($i=0;$i<count($node_queue[0]->children);$i++) 
			{
				if($node_queue[0]->children[$i]->name == $origin_name)
				{
					if(count($node_queue[0]->children[$i]->children) > 0)
						$node["children"] = $node_queue[0]->children[$i]->children;
					$node_queue[0]->children[$i] = $node;
					$key = array();
					array_push($key, $origin_name);
					deleteKeyName($key);
					$json_str_new = json_encode($json_obj);
					file_put_contents($data_file, $json_str_new);
					return true;
				}
				if(count($node_queue[0]->children[$i]->children)>0)
				{
					array_push($node_queue,&$node_queue[0]->children[$i]);
				}
			}
		}
		array_shift($node_queue);
	}
	return false;
}

function insertAndDump(&$children, &$obj, &$json_obj)
{
	if(!isset($_COOKIE['data_file']))
	{
		$data_file = "test.json";
	}
	else
	{
		$data_file = $_COOKIE['data_file'];
	}

	if(count($children) <= 0)
	{
		$children = array();			
		array_push($children,$obj);
	}
	else
	{
		array_push($children,$obj);
	}
	$json_str_new = json_encode($json_obj);
	file_put_contents($data_file, $json_str_new);
}

function insertNode($parent,$node)
{
	if(!isset($_COOKIE['data_file']))
	{
		$data_file = "test.json";
	}
	else
	{
		$data_file = $_COOKIE['data_file'];
	}
	$has_dup_key = checkDupName($node['name']);
	if($has_dup_key)
		return false;
	$obj = $node;

	$json_str = file_get_contents($data_file);
	$json_obj = json_decode($json_str);
	$node_queue = array();
	array_push($node_queue, &$json_obj);
	if($json_obj->name == $parent)
	{
		insertAndDump($json_obj->children,$obj,$json_obj);
		return true;
	}
	while(count($node_queue) > 0)
	{
		if(count($node_queue[0]->children) > 0)
		{
			for ($i=0;$i<count($node_queue[0]->children);$i++) 
			{
				if($node_queue[0]->children[$i]->name == $parent)
				{
					insertAndDump($node_queue[0]->children[$i]->children,$obj,$json_obj);
					return true;
				}
				if(count($node_queue[0]->children[$i]->children)>0)
				{
					array_push($node_queue,&$node_queue[0]->children[$i]);
				}
			}
		}
		array_shift($node_queue);
	}
	return false;
 }

/*
检查用户上传的json文件
*/
 function validateJson($path)
 {
 	$json_str = file_get_contents($path);
 	$json_obj = json_decode($json_str);
 	if($json_obj == NULL)
 	{
 		echo '<p class="info">The file provided is not a validate json file.</p>';
 		return false;
 	}
	$node_queue = array();
	$keys = array();
	array_push($node_queue,&$json_obj);
	array_push($keys,$json_obj->name);

	while(count($node_queue) > 0)
	{
		if(count($node_queue[0]->children) > 0)
		{
			for($i=0;$i<count($node_queue[0]->children);$i++) 
			{
				if(array_key_exists($node_queue[0]->children[$i]->name,$keys))
				{
					echo '<p class="info">Duplicated key in json file.</p>';
					return false;
				}
				else
				{
					array_push($keys,$node_queue[0]->children[$i]->name);
				}
				if(count($node_queue[0]->children[$i]->children)>0)
				{
					array_push($node_queue,&$node_queue[0]->children[$i]);
				}
			}
		}
		array_shift($node_queue);
	}
	return true;
 }

 function deleteKeyName($name_array)
 {
 	if(!isset($_COOKIE['data_file']))
	{
		$data_file = "test.json";
	}
	else
	{
		$data_file = $_COOKIE['data_file'];
	}
	$keys_file = $data_file."keys";
	$keys = array();
	if(file_exists($keys_file))
	{
		$handle = fopen($keys_file, 'r');
		while(!feof($handle))
		{
			$line = fgets($handle);
			if(in_array(trim($line),$name_array))
				continue;
			array_push($keys, trim($line));
		}
		fclose($handle);
		$handle = fopen($keys_file,'w');
		foreach ($keys as $key) {
			fwrite($handle, $key."\n");
		}
		fclose($handle);
	}
 }
/*
name要求唯一，所以所有更新name的操作（目前有增和改）都要先检查，同时也要维护这个文件
*/
 function checkDupName($name)
 {
 	if(!isset($_COOKIE['data_file']))
	{
		$data_file = "test.json";
	}
	else
	{
		$data_file = $_COOKIE['data_file'];
	}
	$has_dup_key = false;
	$keys_file = $data_file."keys";
	if(file_exists($keys_file))
	{
		$handle = fopen($keys_file,'r');
		while(!feof($handle))
		{
			$line = fgets($handle);
			if(trim($line) == $name)
			{
				$has_dup_key = true;
				break;
			}
		}
		fclose($handle);
		if(!$has_dup_key)
		{
			$handle = fopen($keys_file,'a');
			fwrite($handle, $name."\n");
			fclose($handle);
		}
	}
	else
	{
		$handle = fopen($keys_file,'w');
		$json_str = file_get_contents($data_file);
 		$json_obj = json_decode($json_str);
 		$node_queue = array();
		$keys = array();
		array_push($node_queue,&$json_obj);
		array_push($keys,$json_obj->name);

		while(count($node_queue) > 0)
		{
			if(count($node_queue[0]->children) > 0)
			{
				for($i=0;$i<count($node_queue[0]->children);$i++) 
				{
					if(array_key_exists($node_queue[0]->children[$i]->name,$keys))
					{
						$has_dup_key  = true;
					}
					else
					{
						array_push($keys,$node_queue[0]->children[$i]->name);
						fwrite($handle,$node_queue[0]->children[$i]->name."\n");
					}
					if(count($node_queue[0]->children[$i]->children)>0)
					{
						array_push($node_queue,&$node_queue[0]->children[$i]);
					}
				}
			}
			array_shift($node_queue);
		}
		fclose($handle);
	}
	return $has_dup_key;
 }
/*
默认行为是，交换以这两个节点为根的子树
*/
 function swapNode($node1, $node2)
 {
 	
 	if(!isset($_COOKIE['data_file']))
	{
		$data_file = "test.json";
	}
	else
	{
		$data_file = $_COOKIE['data_file'];
	}
	
	$json_str = file_get_contents($data_file);
	$json_obj = json_decode($json_str);
	if($node1==$json_obj->name || $node2==$json_obj->name)
		return false;
	$node_queue = array();
	array_push($node_queue, &$json_obj);
	while(count($node_queue) > 0)
	{
		if(count($node_queue[0]->children) > 0)
		{
			for($i=0;$i<count($node_queue[0]->children);$i++)
			{
				if($node_queue[0]->children[$i]->name == $node1)
					$node_ptr1 = &$node_queue[0]->children[$i];
				if($node_queue[0]->children[$i]->name == $node2)
					$node_ptr2 = &$node_queue[0]->children[$i];
				if(isset($node_ptr1) && isset($node_ptr2))
				{
					$temp = $node_ptr1;
					$node_ptr1 = $node_ptr2;
					$node_ptr2 = $temp;
					$json_str_new = json_encode($json_obj);
					file_put_contents($data_file, $json_str_new);
					return true;
				}
				if(count($node_queue[0]->children[$i]->children)>0)
				{
					array_push($node_queue,&$node_queue[0]->children[$i]);
				}
			}
		}
		array_shift($node_queue);
	}
	return false;
 }

 /**
 * 获得子数的所有节点的name字段，放入数组中。传入参数为json对象
 */
 function getSubtreeNodes($root)
 {
 	$node_queue = array();
 	$node_list = array();
	array_push($node_queue, &$root);
	while (count($node_queue)>0) 
	{
		if(count($node_queue[0]->children) > 0)
		{
			for ($i=0; $i < count($node_queue[0]->children); $i++) { 
				array_push($node_queue,&$node_queue[0]->children[$i]);
			}
		}
		array_push($node_list, $node_queue[0]->name);
		array_shift($node_queue);
	}
	return $node_list;
 }

 function queryNode($name)
 {
 	if(!isset($_COOKIE['data_file']))
	{
		$data_file = "test.json";
	}
	else
	{
		$data_file = $_COOKIE['data_file'];
	}
	$json_str = file_get_contents($data_file);
	$json_obj = json_decode($json_str);
	if($json_obj->name == $name)
	{
		$res_obj = $json_obj;
		if(isset($res_obj->children))
		{
			unset($res_obj->children);
		}
		$res_str = json_encode($res_obj);
		return $res_str;
	}
	$node_queue = array();
	array_push($node_queue, &$json_obj);
	while(count($node_queue) > 0)
	{
		if(count($node_queue[0]->children) > 0)
		{
			for ($i=0;$i<count($node_queue[0]->children);$i++) 
			{
				if($node_queue[0]->children[$i]->name == $name)
				{
					$res_obj = $node_queue[0]->children[$i];
					if(isset($res_obj->children))
					{
						unset($res_obj->children);
					}
					$res_str = json_encode($res_obj);
					return $res_str;
				}
				if(count($node_queue[0]->children[$i]->children)>0)
				{
					array_push($node_queue,&$node_queue[0]->children[$i]);
				}
			}
		}
		array_shift($node_queue);
	}
	return "";
 }
?>