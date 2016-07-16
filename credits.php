<!DOCTYPE html>
<head>
<?php require 'db.php';

  $user = $_COOKIE["user"];
  $credits = -1;
  if (logged_in()) {
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM credits WHERE username = '".$user."'");
    $credits = intval($result->fetch_array(MYSQL_ASSOC)["credits"]);
    DBC();
  }
?>
</head>
<body>
<?php
  echo $credits;
?>
</body>
</html>