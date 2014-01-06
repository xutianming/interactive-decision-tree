<?php


require_once("json_util.php");

$name = $_GET['name'];

if(empty($name))
{
	echo '<p class="info">Node name must be specified to query.</p>';
}
else
{
	$response = queryNode($name);
	echo $response;
}

?>
