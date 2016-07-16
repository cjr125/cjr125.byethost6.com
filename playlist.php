<?php

$fileloc = "";
$filename = "";

echo "content length: " . $_SERVER['CONTENT_LENGTH'] . " <br/>";
echo "request method: " . $_SERVER['REQUEST_METHOD'] . " <br/>";
echo "posted files: " . count($FILES) . " ";

if ((($_FILES["uploaded"]["type"] == "audio/mpeg") || ($_FILES["uploaded"]["type"] == "audio/mpeg3")) && ($_FILES["uploaded"]["size"] < 10000000)) {
  if ($_FILES["uploaded"]["error"] > 0) {
    echo "Return Code: " . $_FILES["uploaded"]["error"] . "<br />";
  } 
  else {
    echo "Upload: " . $_FILES["uploaded"]["name"] . "<br />";
    echo "Type: " . $_FILES["uploaded"]["type"] . "<br />";
    echo "Size: " . ($_FILES["uploaded"]["size"] / 1024) . " Kb<br />";
    echo "Temp file: " . $_FILES["uploaded"]["tmp_name"] . "<br />";
    
    $fileloc =  "audio/" . $_FILES["uploaded"]["name"];
    $filename = $_FILES["uploaded"]["name"];

    if (file_exists("audio/" . $_FILES["uploaded"]["name"])) {
      echo $_FILES["uploaded"]["name"] . " already exists. ";
      echo "<br /><a href='$fileloc'>$filename</a>";
    }
    else {
      move_uploaded_file($_FILES["uploaded"]["tmp_name"],
      "audio/" . $_FILES["uploaded"]["name"]);
      echo "Stored in: " . "audio/" . $_FILES["uploaded"]["name"];
      
      echo "<br /><a href='$fileloc'>$filename</a>";
    }
  }
}
else if ($_FILES["uploaded"]["size"] > 0) {
  echo "Invalid file";
}
?>

<html>
<head>
  <title>Upload your contribution (mp3 only)</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <script type="text/javascript" src="http://webplayer.yahooapis.com/player.js"></script> 
</head>
<body>
  <form action="playlist.php" method="POST" enctype="multipart/form-data">
      <label for="file">Filename:</label>
      <input type="file" name="uploaded" />
      <br />
      <input type="submit" name="submit" value="Submit" />
  </form>
</body>
</html> 