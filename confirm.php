<!DOCTYPE html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $error = "";
  $return_url = $_GET["return_url"];

  if (isset($_POST["hf_destination_url"]) && $_POST["hf_destination_url"] != "") {
    header("Location: ".$_POST["hf_destination_url"]);
  }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_submit").on("click", function() {
      $("#hf_destination_url").val(<?=$return_url?>);
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
    echo "<form id='form1' name='form1' action='confirm.php' method='post'>\n";
    echo "Confirm<br>\n";
    echo "<input type='button' id='btn_submit' name='btn_submit' value='Submit' />\n";
    echo "</form>\n";
  }
  else {
    header("Location: cms.php");
  }
?>
</body>
</html>