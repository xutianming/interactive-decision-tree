<?php
if (($_FILES["file"]["type"] == "application/json")
    && ($_FILES["file"]["size"] < 20000))
{
    if ($_FILES["file"]["error"] > 0)
    {
      echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
    else
    {
      setcookie('data_file',''.$_FILES["file"]["name"],time()+3600);
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
<?php
      echo "Upload: " . $_FILES["file"]["name"] . "<br />";
      echo "Type: " . $_FILES["file"]["type"] . "<br />";
      echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
      echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

      if(file_exists($_FILES["file"]["name"]))
      {
        echo $_FILES["file"]["name"] . " already exists. ";
      }
      else
      {
        move_uploaded_file($_FILES["file"]["tmp_name"],$_FILES["file"]["name"]);
        echo "Stored in: " . $_FILES["file"]["name"];
      }
    }
}
else
{
    echo "Invalid file";
}
echo "_________________________".$_COOKIE['data_file'];
require_once('json_util.php');
require_once('visual_control.php');
?>
</body>
</html>