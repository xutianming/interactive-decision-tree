<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<link rel="stylesheet" href="styles.css"/>
</head>
<body>
<?php
require_once("json_util.php");
require_once("Node.class.php");
$output_form = false;
if (!isset($_POST['submit']))
{
	if(isset($_GET['name']))
	{
		// Name of the node to update.
		$name = $_GET['name'];
		$size = $_GET['size'];
	}
	else
	{
		echo '<p class="error">Sorry,no node was specified to update.</p>';
	}

	if(isset($name))
	{
		$output_form = true;
	}
}

if(isset($_POST['submit']))
{
	$node_name = $_POST['inputname'];
	$node_size = $_POST['inputsize'];
	$origin_name = $_POST['originname'];
	if(empty($node_name) || empty($node_size))
	{
		echo '<p class="error">Both 2 fields should be filled.</p>';
		$output_form = true;
	}
	else
	{
		// Actually add the node to json data file.
		$node = new Node();
		$node->name = $node_name;
		$node->size = $node_size;
		$flag = updateNode($origin_name,$node);
		if($flag)
		{
			echo '<p>Update node succeed.</p>';
			require_once("visual_control.php");
		}
		else
		{
			echo '<p>Update node failed.</p>';
		}
	}
}
if($output_form)
{
?>
<h2>Please enter the information to update.</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<label for="inputname">Name:</label><br/>
<input id="inputname" name="inputname" type="text" size="30"/><br/>
<label for="inputsize">Size:</label><br/>
<input id="inputsize" name="inputsize" type="text" size="10"/><br/>
<label for="originname">Origin Name:</label><br/>
<input id="originname" name="originname" type="text" size="30" readonly="readonly" value="<?php echo $name ?>"/><br/>
<input type="submit" name="submit" value="Submit"/>
</form>
<?php
}
?>
</body>
</html>