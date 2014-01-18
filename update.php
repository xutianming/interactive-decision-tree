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
</head>
<body>
<script src="js/jquery-1.9.1.js"></script>
<script src="js/util.js"></script>
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
		echo '<p class="info">Sorry,no node was specified to update.</p>';
	}

	if(isset($name))
	{
		$output_form = true;
	}
}
?>
<script>
// bind event to addAttrBtn
/**
$(function()
{
var xmlhttp = new XMLHttpRequest();
xmlhttp.open("GET","query.php?name=<?php echo $name;?>",false);
xmlhttp.send();
console.log(xmlhttp.responseText);
var response_str = parseResponse2DataForm(xmlhttp.responseText);
*/
/**
parseResponse2DataForm要把ajax响应转化为
var response_str = '<label for="attributename1">Attribute Name:</label></br>' + 
		  		   '<input id="attributename1" name="attributename1" type="text" size="30" value="attr1"/></br>'+
		  		   '<label for="attributevalue1">Attribute Value:</label></br>'+
		  		   '<input id="attributevalue1" name="attributevalue1" type="text" size="30" value="val1"/></br>'+
		  		   '<input id="optionalattributenum" name="optionalattributenum" style="display:none;" value="1"/>';
*/
/**
var dataform = $('#dataform');
$(response_str).appendTo(dataform);
var i = $('#optionalattributenum').attr('value')+1;
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
if(isset($_POST['submit']))
{
	$node_name = $_POST['inputname'];
	$node_size = $_POST['inputsize'];
	$origin_name = $_POST['originname'];
	if(empty($node_name) || empty($node_size))
	{
		echo '<p class="info">Both 2 fields should be filled.</p>';
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
		$flag = updateNode($origin_name,$node);
		if($flag)
		{
			echo '<p class="info">Update node succeed.</p>';
			require_once("visual_control.php");
		}
		else
		{
			echo '<p class="info">Update node failed.</p>';
		}
	}
}
if($output_form)
{
?>
<h2>Please enter the information to update.</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<div id = "dataform">
<label for="inputname">Name:</label><br/>
<input id="inputname" name="inputname" type="text" size="30"/><br/>
<label for="inputsize">Size:</label><br/>
<input id="inputsize" name="inputsize" type="text" size="10"/><br/>
<label for="originname">Origin Name:</label><br/>
<input id="originname" name="originname" type="text" size="30" readonly="readonly" value="<?php echo $name ?>"/><br/>
</div>
<input type="submit" name="submit" value="Submit"/>
<button type="button" id="addAttrBtn">增加属性</button>
</form>
<?php
}
?>
</body>
</html>
<?php
//}
?>