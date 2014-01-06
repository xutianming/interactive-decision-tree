<?php
require_once("json_util.php");


$node1 = $_GET['node1'];
$node2 = $_GET['node2'];

if(empty($node1) || empty($node2))
{
	echo '<p class="info">Both node should be specified to swap node.</p>';
}
else
{
	$flag = swapNode($node1, $node2);
	if($flag)
		echo '<p class="info">Swap node succeeded.</p>';
	else
		echo '<p class="info">Fail to swap node.Please check if you did click 2 different node.</p>';
}
?>