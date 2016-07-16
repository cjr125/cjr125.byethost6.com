<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $error = "";
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_choose_upgrade").on("click", function() {
      $("#hf_upgrade_id").val($(this).parent().firstChild().innerHtml();
      $("#form1").submit();
    });
  });
</script>
</head>
<body>
<?php
  if (logged_in()) {
    echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    if ($error != "") {
      echo "<span class='error'>".$error."</span><br>\n";
    }
    echo "<form id='form1' name='form1' action='upgrades.php' method='post'>\n";
    echo "<table id='tbl_upgrades'>\n";
    echo "<tr><th>id</th><th>name</th><th>description</th><th></th></tr>\n";
    $upgrades = getUpgrades();
    foreach ($upgrades as $upgrade) {
      $id = $upgrade["id"];
      $name = $upgrade["name"];
      $description = $upgrade["description"];
      echo "<tr><td>".$id."</td><td>".$name."</td><td>".$description."</td><td><input type='button' id='btn_choose_upgrade' name='btn_choose_upgrade' value='Choose Upgrade' /></td></tr>\n";
    }
    echo "</table>\n";
    echo "<input type='hidden' id='hf_upgrade_id' name='hf_upgrade_id' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getUpgrades() {
    $uprades = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM upgrades WHERE username = '".$user."'");
    if ($result->num_rows == 0) {
      echo "No rows found.";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $upgrades[] = $row;
    }
    DBC();
    return $upgrades;
  }
?>
</body>
</html>