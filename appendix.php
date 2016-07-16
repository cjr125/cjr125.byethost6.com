<!DOCTYPE html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $error = "";
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
  });
</script>
</head>
<body>
<?php
  if (logged_in()) {
    echo "Logged in as ".$_COOKIE["user"]." <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    if ($error != "") {
      echo "<span class='error'>".$error."</span><br>\n";
    }
    echo "<form id='form1' name='form1' action='appendix.php' method='post'>\n";
    echo "<table id='tbl_appendix'>\n";
    echo "<tr><th>category</th><th>description</th></tr>\n";
    $appendix = getAppendix();
    foreach ($appendix as $appendix_category) {
      $category = $appendix["category"];
      $description = $appendix["description"];
      echo "<tr><td>".$category."</td><td>".$description."</td></tr>\n";
    }
    echo "</table>\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getAppendix() {
    $appendix = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM appendix");
    if ($result->num_rows == 0) {
      echo "No rows found.";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $appendix[] = $row;
    }
    DBC();
    return $appendix;
  }
?>
</body>
</html>