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
$output_form = true;
if(isset($_POST["submit"]))
{
	$node_name = $_POST["inputname"];
	$node_size = $_POST["inputsize"];
	$node_parent = $_POST["inputparent"];
	if(empty($node_name) || empty($node_size) || empty($node_parent))
	{
		$output_form = true;
		echo "<p>All those fields should be filled.</p>";
	}
	else
	{
		// Actually add the node to json data file.
		$node = new Node();
		$node->name = $node_name;
		$node->size = $node_size;
		$flag = insertNode($node_parent,$node);
		if($flag)
		{
			echo '<p>Add node succeed.</p>';
			$output_form = false;
		}
		else
		{
			echo '<p>Add node failed.</p>';
			$output_form = true;
		}
	}
}
if($output_form)
{
?>
<h2>Please enter the information of the node.</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<label for="inputname">Name:</label><br/>
<input id="inputname" name="inputname" type="text" size="30"/><br/>
<label for="inputsize">Size:</label><br/>
<input id="inputsize" name="inputsize" type="text" size="10"/><br/>
<label for="inputparent">Parent:</label></br>
<input id="inputparent" name="inputparent" type="text" size="30"/><br/>
<input type="submit" name="submit" value="Submit"/>
</form>
<?php
}
?>
</body>
</html>