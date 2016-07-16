<!DOCTYPE html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $error = "";
  $game_state_id = $_GET["game_state_id"];
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#sector").on("click", function() {
      window.location = "darts.php?game_state_id=" + <?=$game_state_id?> + 1;
    });
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
    echo "<form id='form1' name='form1' action='darts.php' method='post'>\n";
    echo "<table id='tbl_darts'>\n";
    $dartboard_sectors = getDartboardSectors($game_state_id);
    echo "<tr><td>".$dartboard_sectors."</td></tr>\n";
    echo "</table>\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getDartboardSectors($game_state_id) {
    $dartboard_sectors = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM darts WHERE game_state_id = ".$game_state_id);
    if ($result->num_rows == 0) {
      echo "No rows found.";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $dartboard_sectors[] = $row;
    }
    DBC();
    return $dartboard_sectors;
  }
?>
</body>
</html>