<!DOCTYPE html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $error = "";
  $client_id = $_GET["client_id"];
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_refresh").on("click", function() {
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
    echo "<form id='form1' name='form1' action='supporters.php' method='post'>\n";
    echo "<table id='tbl_supporters'>\n";
    echo "<tr><th>supporter name</th><th>contribution</th><th>date</th></tr>\n";
    $supporters = getSupporters($client_id);
    foreach ($supporters as $supporter) {
      $supporter_name = $supporter["name"];
      $contribution = $supporter["contribution"];
      $date = $supporter["date"];
      echo "<tr><td>".$supporter_name."</td><td>".$contribution."</td><td>".$date."</td></tr>\n";
    }
    echo "<tr><td colspan='2'></td><td><input type='button' id='btn_refresh' name='btn_refresh' value='Refresh' /></td></tr>\n";
    echo "</table>\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getSupporters($client_id) {
    $supporters = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM supporters WHERE client_id = ".$client_id);
    if ($result->num_rows == 0) {
      echo "No rows found.";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $supporters[] = $row;
    }
    DBC();
    return $supporters;
  }
?>
</body>
</html>