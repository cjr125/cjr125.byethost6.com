<!DOCTYPE html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $error = "";
  $page_title = $_GET["page_title"];
?>
</head>
<body>
<?php
  if (isset($page_title) && $page_title != "") {
    DBLogin("a8823305_audio");
    $breadcrumbs = array();
    $result = DBQuery("SELECT * FROM breadcrumbs WHERE page_title = ".$page_title);
    if ($result->num_rows == 0) {
      echo "No rows found.";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $breadcrumbs[] = $row;
    }
    DBC();
    foreach ($breadcrumbs as $breadcrumb) {
      echo "<a href='".$breadcrumb["url"];."'>".$breadcrumb["page_title"]."</a>";
    }
  }
?>
</body>
</html>