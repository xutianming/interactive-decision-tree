<?php
require_once('admin/login.php');
if(!empty($_COOKIE['user_id']))
{
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<link rel="stylesheet" href="styles.css"/>
<link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css"/>
<link rel="stylesheet" href="css/jquery-ui.css">
<title>Data analyser</title>
</head>
<body>
<div id="toolbar">
<span id="logo">数据可视分析平台</span>
<span id="tree1" class="button">双曲树视图</span>
<span id="tree2" class="button">视图二</span>
<span id="addBtn" class="button">增加节点</span>
<span id="updateBtn" class="button">更新节点</span>
<span id="deleteBtn" class="button">删除节点</span>
<span id="swapBtn" class="button">交换节点</span>
<span id="detailsBtn" class="button">显示明细</span>
</div>
<div id="message">欢迎使用数据可视分析平台!</div>
<div id="graph"></div>
<div id="right-box">
	<div id="right-box-inner">
		<div id = "right-box-slider">
			<h2>节点大小</h2>
			当前筛选阈值：<span id="size-val">1</span>
  			<div id="size-slider" class="slider">
  			</div>
		</div>
		<div id="right-box-uploader" class="uploader">
		<h2>上传文件</h2>
		<form action="upload_file.php" method="post" enctype="multipart/form-data">
		<label for="file">Filename:</label>
		<input type="file" name="file" id="file" />
		<br />
		<input type="submit" name="submit" value="提交文件" />
		</form>
		</div>
		<div id="right-box-form-set">
			<h2>表单填写</h2>
  			<div id="add-form" style="display: none;">
    			<form method="post" action="add.php">
      			<div id = "add-dataform">
        			<label for="inputname">Name:</label><br/>
        			<input id="add-inputname" name="inputname" type="text" size="30"/><br/>
        			<label for="inputsize">Size:</label><br/>
        			<input id="add-inputsize" name="inputsize" type="text" size="10"/><br/>
        			<label for="inputparent">Parent:</label></br>
        			<input id="add-inputparent" name="inputparent" type="text" size="30" readonly="readonly"/><br/>
        			<input id="add-optionalattributenum" name="optionalattributenum" style="display:none;" value="0"/>
      			</div>
      			<input type="submit" name="submit" value="Submit"/>
    			</form>
    			<button type="button" id="add-addAttrBtn">增加属性</button>
  			</div>
 		 	<div id="update-form" style="display: none;">
    			<form method="post" action="update.php">
      				<div id = "update-dataform">
        				<label for="inputname">Name:</label><br/>
        				<input id="update-inputname" name="inputname" type="text" size="30"/><br/>
        				<label for="inputsize">Size:</label><br/>
        				<input id="update-inputsize" name="inputsize" type="text" size="10"/><br/>
        				<label for="originname">Origin Name:</label><br/>
        				<input id="update-originname" name="originname" type="text" size="30" readonly="readonly" /><br/>
      				</div>
      				<input type="submit" name="submit" value="Submit"/>
      				<button type="button" id="update-addAttrBtn">增加属性</button>
    			</form>
  			</div>
		</div>
	</div>
</div>
<div id="datatable"></div>
<div id="divContext"
    style="border: 1px solid blue; display: none;width:150px;">
    <ul class="cmenu">
        <li><a id="addChildren" href="#">增加分支</a></li>
        <li><a id="delChildren" href="#">删除分支</a></li>
        <li><a id="updateNode" href="#">修改节点</a></li>
        <li><a id="showSubVis" href="#">显示子图</a></li>
        <li class="topSep">
            <a id="aDisable" href="#">disable this menu</a>
        </li>
    </ul>
</div>
<?php

if(!isset($_COOKIE['data_file']))
{
	$_COOKIE['data_file'] = 'test.json';
}
require_once('visual_control.php');
?>
</body>
</html>
<?php
}
?>