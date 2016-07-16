<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $first = "";
  $last = "";
  $email = "";
  DBLogin("a8823305_audio");
  $result = DBQuery("SELECT first,last,email FROM users WHERE username = '".$user."'");
  if ($result->num_rows > 0) {
    $row = $result->fetch_array(MYSQL_ASSOC);
    $first = $row["first"];
    $last = $row["last"];
    $email = $row["email"];
  }
  if (isset($_POST["hf_email"]) && $_POST["hf_email"] != "") {
    $email = $_POST["hf_email"];
  }
  if (isset($_POST["btn_submit"])) {
    if ($email != "" && $last != "" && $first != "") {
      DBQuery("UPDATE users SET accountStatus = 'confirmed'");
    }
  }
  DBC();
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_submit").on("click", function() {
      clearHiddenFields();
      $("#form1").submit();
    });
    function clearHiddenFields() {
      $("#hf_email").val("");
    }
  });
</script>
<style type="text/css">
  #form1 h4 {
    text-transform:capitalize;
  }
</style>
</head>
<body>
<?php
  if (logged_in()) {
    echo "<form id='form1' name='form1' action='thanks.php' method='post'>\n";
    echo "<h4>Dear ".$first." ".$last.",</h4>\n";
    echo "<b>Thank you for registering an account with the address ".$email.".</b>\n";
    echo "<span class='confirmationInstructionContainer'>Confirm this is the correct e-mail by clicking below.</span>\n";
    echo "<input type='button' id='btn_submit' name='btn_submit' value='Submit' />\n";
    echo "<input type='hidden' id='hf_email' name='hf_email' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
?>
</body>
</html>