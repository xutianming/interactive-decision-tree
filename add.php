<?php
//require_once('admin/login.php');
//if(!empty($_COOKIE['user_id']))
//{
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<link rel="stylesheet" href="styles.css"/>
<link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css"/>
<link rel="stylesheet" href="css/jquery-ui.css">
<?php
if(isset($_POST['submit']))
{
	$node_name = $_POST['inputname'];
?>
<style>
#<?php echo $node_name;?> {
	fill: #FF0000;
}
</style>
<?php
}
?>
</head>
<body>
<script src="js/jquery-1.9.1.js"></script>
<script>

// bind event to addAttrBtn
/**
$(function()
{
var i = 1;
$('#addAttrBtn').click(
	function(){
		var dataform = $('#dataform');
		$('<label for="attributename'+i+'">Attribute Name:</label></br>' + 
		  '<input id="attributename'+i+'" name="attributename'+i+'" type="text" size="30"/></br>'+
		  '<label for="attributevalue'+i+'">Attribute Value:</label></br>'+
		  '<input id="attributevalue'+i+'" name="attributevalue'+i+'" type="text" size="30"/></br>')
		.appendTo(dataform);
		$('#optionalattributenum').attr('value',i);
		i++;
	});
});
*/
</script>
<?php
require_once("json_util.php");
require_once("Node.class.php");
$output_form = false;
if (!isset($_POST['submit']))
{
	if(isset($_GET['name']))
	{
		// Name of the parent node for adding.
		$name = $_GET['name'];
	}
	else
	{
		//echo '<p class="info">Sorry,no node was specified to add children.</p>';
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
	$node_parent = $_POST['inputparent'];
	if(empty($node_name) || empty($node_size))
	{
		//echo '<p class="info">Both 2 fields should be filled.</p>';
		$output_form = true;
	}
	else
	{
		// Actually add the node to json data file.
		$node = array();
		$node["name"] = $node_name;
		$node["size"] = $node_size;
		$attr_num = intval(trim($_POST["optionalattributenum"]));
		for($i=1; $i<=$attr_num; $i++)
		{
			$node[$_POST["attributename".$i]] = $_POST["attributevalue".$i];
		}
		$flag = insertNode($node_parent,$node);
		if($flag)
		{
			//echo '<p class="info">Add node succeed.</p>';
			require_once("visual_control.php");
		}
		else
		{
			//echo '<p class="info">Add node failed.</p>';
		}
	}
}
if($output_form)
{
?>
<!--
<h2>Please enter the information of the child node.</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<div id = "dataform">
<label for="inputname">Name:</label><br/>
<input id="inputname" name="inputname" type="text" size="30"/><br/>
<label for="inputsize">Size:</label><br/>
<input id="inputsize" name="inputsize" type="text" size="10"/><br/>
<label for="inputparent">Parent:</label></br>
<input id="inputparent" name="inputparent" type="text" size="30" value="<?php echo $name; ?>" readonly="readonly"/><br/>
<input id="optionalattributenum" name="optionalattributenum" style="display:none;" value="0"/>
</div>
<input type="submit" name="submit" value="Submit"/>
</form>
<button type="button" id="addAttrBtn">增加属性</button>
-->
<?php
}
?>
</body>
</html>
<?php
//}
?>