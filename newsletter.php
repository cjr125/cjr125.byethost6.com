<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_proceed").on("click", function() {
      window.location = "cms.php";
    });
  });
</script>
</head>
<body>
<?php
  if (logged_in()) {
    echo "Logged in as ".$_COOKIE["user"]." <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    echo "<form id='form1' name='form1' action='newsletter.php' method='post'>\n";
    $newsletter = getLatestNewsletter();
    echo "<table id='tbl_newsletter'>\n";
    echo "<tr><th>id</th><th>title</th><th>subject</th><th>html</th><th>created</th></tr>\n";
    echo "<tr><td>".$newsletter["id"]."</td><td>".$newsletter["title"]."</td><td>".$newsletter["subject"]."</td><td>".$newsletter["html"]."</td><td>".$newsletter["created"]."</td></tr>\n";
    echo "</table>\n";
    echo "<input type='button' id='btn_proceed' name='btn_proceed' value='' />";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getLatestNewsletter() {
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM newsletter ORDER BY created DESC LIMIT 1");
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    return $result;
  }
?>
</body>
</html>