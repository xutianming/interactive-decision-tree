<?php

require_once("Node.class.php");

function deleteNode($name)
{
	$json_str = file_get_contents("test.json");
	$json_obj = json_decode($json_str);
	$node_queue = array();
	array_push($node_queue, &$json_obj);
	if($json_obj->name == $name)
	{
		unset($json_obj);
		$json_str_new = json_encode($json_obj);
		file_put_contents("test.json", $json_str_new);
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
					array_splice($node_queue[0]->children,$i,1);
					$json_str_new = json_encode($json_obj);
					file_put_contents("test.json", $json_str_new);
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
	$name = $node->getName();
	$size = $node->getSize();

	$json_str = file_get_contents("test.json");
	$json_obj = json_decode($json_str);
	$node_queue = array();
	array_push($node_queue, &$json_obj);
	if($json_obj->name == $origin_name)
	{
		$json_obj->name = $name;
		$json_obj->size = $size;
		$json_str_new = json_encode($json_obj);
		file_put_contents("test.json", $json_str_new);
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
					$node_queue[0]->children[$i]->name = $name;
					$node_queue[0]->children[$i]->size = $size;
					$json_str_new = json_encode($json_obj);
					file_put_contents("test.json", $json_str_new);
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
	file_put_contents("test.json", $json_str_new);
}

function insertNode($parent,$node)
{
	$obj->name = $node->getName();
	$obj->size = $node->getSize();

	$json_str = file_get_contents("test.json");
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
?>