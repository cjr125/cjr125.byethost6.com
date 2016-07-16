<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $new_username = "";
  $old_password = "";
  $new_password = "";
  
  if (isset($_POST["hf_username"]) && $_POST["hf_username"] != "") {
      $new_username = $_POST["hf_username"];
  }
  if (isset($_POST["hf_old_password"]) && $_POST["hf_old_password"] != "") {
    $old_password = $_POST["hf_old_password"];
  }
  if (isset($_POST["hf_new_password"]) && $_POST["hf_new_password"] != "") {
    $new_password = $_POST["hf_new_password"];
  }
  if (isset($_POST["btn_submit"])) {
    if ($new_password != "" && $old_password != "" && $user != "") {
      DBLogin("a8823305_audio");
      $result = DBQuery("SELECT password FROM users WHERE username = '".$user."')");
      if ($result->num_rows > 0) {
        $row = $result->fetch_row();
        $password = $row["password"];
        if ($old_password == $password) {
          DBQuery("UPDATE users SET username = '".$new_username."', password = '".$new_password."' WHERE username = '".$user."'");
        }
      }
      DBC();
    }
  }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_submit").on("click", function() {
      clearHiddenFields();
      $("#hf_username").val($("#tb_username").val());
      $("#hf_old_password").val($("#tb_old_password").val());
      if ($("#tb_new_password").val() == $("#tb_confirm_new_password").val()) {
        $("#hf_new_password").val($("#tb_new_password").val());
        $("#form1").submit();
      } else {
        $("<span class='error'>The passwords did not match.</span>").insertBefore("#tbl_reset_password");
      }
    });
    function clearHiddenFields() {
      $("#hf_username").val("");
      $("#hf_old_password").val("");
      $("#hf_new_password").val("");
    }
  });
</script>
<style type="text/css">
  .error {
    color: red;
  }
</style>
</head>
<body>
<?php
  if (logged_in()) {
    echo "Logged in as ".$_COOKIE["user"]." <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    echo "<form id='form1' name='form1' action='resetpassword.php' method='post'>\n";
    echo "<table id='tbl_reset_password'>\n";
    echo "<tr><th>username</th><th>old password</th><th>new password</th><th>confirm new password</th><th></th></tr>\n";
    echo "<tr><td><input type='text' id='tb_username' name='tb_username' value='".$user."' /></td>\n";
    echo "<td><input type='password' id='tb_old_password' name='tb_old_password' value='".$old_password."' /></td>\n";
    echo "<td><input type='password' id='tb_new_password' name='tb_new_password' value='".$new_password."' /></td>\n";
    echo "<td><input type='password' id='tb_confirm_new_password' name='tb_confirm_new_password' value='".$confirm_new_password."' /></td>\n";
    echo "<td><input type='button' id='btn_submit' name='btn_submit' value='Submit' /></td></tr>\n";
    echo "</table>\n";
    echo "<input type='hidden' id='hf_username' name='hf_username' value='' />\n";
    echo "<input type='hidden' id='hf_old_password' name='hf_old_password' value='' />\n";
    echo "<input type='hidden' id='hf_new_password' name='hf_new_password' value='' />\n"
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
</body>
</html>