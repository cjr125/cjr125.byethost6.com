<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];

  if (isset($_COOKIE["page"]) && $_COOKIE["page"] != "welcome.php") {
    header("Location: ".$_COOKIE["page"]);
  }
  setcookie('page', 'welcome.php');
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() { 
    $("#btn_proceed").on("click", function() {
      window.location = "http://www.cjr125.netai.net/proceed.php";
    });
  });
</script>
</head>
<body>
<?php
  if (logged_in()) {
    echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    echo "<form id='form1' name='form1' action='welcome.php' method='post'>\n";
    echo "<div class='instructions'>\n";
    echo "To begin creating a composition, go to the Options menu and make sure your playback, recording and midi settings are correct.
          Then, go to the File menu and select 'New' to create a new composition. Once you have named your composition, go to the Create 
          menu and select 'Track' to add a new track to the view. Alternatively, to add a software instrument track, select 'Sequence' 
          from the Create menu.";
    echo "</div><br>\n";
    echo "<input type='button' id='btn_proceed' name='btn_proceed' value='Proceed' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
?>
</body>
</html>