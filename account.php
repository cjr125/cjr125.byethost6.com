<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];

  $email = "";
  $new_email = "";
  $accountStatus = "";

  if (isset($_POST["hf_email"]) && $_POST["hf_email"] != "") {
    $new_email = $_POST["hf_email"];
  }
  if (isset($_POST["btn_submit"])) {
    if ($new_email != "" && $user != "") {
      DBLogin("a8823305_audio");
      DBQuery("UPDATE users SET email = '".$new_email."' WHERE user = '".$user."'");
      DBC();
    }
  }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_submit").on("click", function() {
      clearHiddenFields();
      $("#hf_new_email").val($("#tb_email").val());
      $("#form1").submit();
    });
    function clearHiddenFields() {
      $("#hf_new_email").val("");
    }
  });
</script>
</head>
<body>
<?php
  if (logged_in()) {
    echo "Logged in as ".$_COOKIE["user"]." <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT email,accountStatus FROM users WHERE username = '".$user."'");
    if ($result->num_rows > 0) {
      $row = $result->fetch_row();
      $email = $row["email"];
      $accountStatus = $row["accountStatus"];
    }
    DBC();
    echo "<form id='form1' name='form1' action='account.php' method='post'>\n";
    echo "<table id='tbl_account'>\n";
    echo "<tr><th>email</th><th>account status</th><th><a href=\"resetpassword.php\">Reset Password</a><th></tr>\n";
    echo "<tr><td><input type='text' id='tb_email' name='tb_email' value='".$email."' /></td>\n";
    echo "<td>".$accountStatus."</td>\n";
    echo "<td><input type='button' id='btn_submit' name='btn_submit' value='Submit' /></td></tr>\n";
    echo "</table>\n";
    echo "<input type='hidden' id='hf_new_email' name='hf_new_email' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
?>
    