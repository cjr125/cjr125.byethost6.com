<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $first_name = "";
  $last_name = "";
  $username = "";
  $userError = "";
  $password = "";
  $email = "";
  $accountStatus = "";
  $gender = "";
  $dateOfBirth = new DateTime("0000-00-00 00:00:00");
  if (isset($_POST["hf_first_name"]) && $_POST["hf_first_name"] != "") {
    $first_name = $_POST["hf_first_name"];
  }
  if (isset($_POST["hf_last_name"]) && $_POST["hf_last_name"] != "") {
    $last_name = $_POST["hf_last_name"];
  }
  if (isset($_POST["hf_username"]) && $_POST["hf_username"] != "") {
    $username = $_POST["hf_username"];
  }
  if (isset($_POST["hf_password"]) && $_POST["hf_password"] != "") {
    $password = $_POST["hf_password"];
  }
  if (isset($_POST["hf_email"]) && $_POST["hf_email"] != "") {
    $email = $_POST["hf_email"];
  }
  if (isset($_POST["hf_gender"]) && $_POST["hf_gender"] != "") {
    $gender = $_POST["hf_gender"];
  }
  $tb_date_of_birth = "MM/DD/YYYY";
  if (isset($_POST["hf_date_of_birth"]) && $_POST["hf_date_of_birth"] != "") {
    $dateOfBirth = new DateTime(str_replace("/","-",$_POST["hf_date_of_birth"])." 00:00:00");
  }
  if (isset($_POST["btn_submit"])) {
    if ($dateOfBirth != (new DateTime("0000-00-00 00:00:00")) && $gender != "" && $email != "" && $password != "" && $username != "" && $first_name != "" && $last_name != "") {
      DBLogin("a8823305_audio");
      $result = DBQuery("IF NOT EXISTS(SELECT username FROM users WHERE username = '".$username."')
                           INSERT INTO users (first,last,username,password,email,confirmationCode,gender,accountStatus,dateOfBirth,registrationDate) VALUES ('".$first_name."','".$last_name."','".$username."','".$password."','".$email."','".getRandomString()."','".$gender."','unverified','".$dateOfBirth."',NOW()");
      if ($result) {
        $id = $result->insert_id();
        setcookie('user', $username);
        header("Location: email_verification.php");
      }
      else {
        $userError = "Username already exists. Please choose another.";
      }
      DBC();
    }
  }
  function getRandomString($length = 30) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';

    for ($i = 0; $i < $length; $i++) {
        $string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    return $string;
  }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
    $("#tb_date_of_birth").on("click", function() {
      if ($("#tb_date_of_birth").val() == "MM/DD/YYYY") {
        $("#tb_date_of_birth").val("");
      }
    });
    $("#btn_submit").on("click", function() {
      clearHiddenFields();
      $("#hf_first_name").val($("#tb_first_name").val());
      $("#hf_last_name").val($("#tb_last_name").val());
      $("#hf_username").val($("#tb_username").val());
      if ($("#tb_password").val() == $("#tb_confirm_password").val()) {
        $("#hf_password").val($("#tb_password").val());
        $("#hf_email").val($("#tb_email").val());
        $("#hf_gender").val($("#dd_gender:selected").val());
        if ($("#tb_date_of_birth").val() != "MM/DD/YYYY") {
          var dateReg = /^\d{2}([./-])\d{2}\1\d{4}$/;
          var date_of_birth = $("#tb_date_of_birth").val();
          if (date_of_birth.match(dateReg)) {
            $("#hf_date_of_birth").val(date_of_birth);
          } else {
            $("<span class='error'>Invalid date format. Please use (MM/DD/YYYY).</span>").insertBefore("#tbl_registration");
        }
        $("#form1").submit();
      } else {
        $("<span class='error'>Passwords don't match</span>").insertBefore("#tbl_registration");
      }
    });
    function clearHiddenFields() {
      $("#hf_first_name").val("");
      $("#hf_last_name").val("");
      $("#hf_username").val("");
      $("#hf_password").val("");
      $("#hf_email").val("");
      $("#hf_date_of_birth").val("");
    }
  });
</script>
<style type='text/css'>
  .error {
    color:red;
  }
</style>
</head>
<body class='registration'>
<?php
  echo "<form id='form1' name='form1' action='registration.php' method='post'>\n";
  echo "<table id='tbl_registration'>\n";
  echo "<tr><td><label for='tb_first_name'>First Name:</label><input type='text' id='tb_first_name' name='tb_first_name' value='".$first_name."' /></td></tr>\n";
  echo "<tr><td><label for='tb_last_name'>Last Name:</lablel><input type='text' id='tb_last_name' name='tb_last_name' value='".$last_name."' /></td></tr>\n";
  echo "<tr><td><label for='tb_username'>Username:</label><input type='text' id='tb_username' name='tb_username' value='".$username."' /></td></tr>\n";
  echo "<tr><td class='userError'><span class='error'>".$userError."</span></td></tr>\n";
  echo "<tr><td><label for='tb_password'>Password:</label><input type='text' id='tb_password' name='tb_password' value='".$password."' /></td></tr>\n";
  echo "<tr><td><label for='tb_confirm_password'>Confirm Password:</label><input type='text' id='tb_confirm_password' name='tb_confirm_password' value='".$password2."' /></td></tr>\n";
  echo "<tr><td><label for='tb_email'>Email:</label><input type='text' id='tb_email' name='tb_email' value='".$email."' /></td></tr>\n";
  echo "<tr><td><label for='dd_gender'>Sex:</label><select id='dd_gender' name='dd_gender'><option>Male</option><option>Female</option></select></td></tr>\n";
  echo "<tr><td><label for='tb_date_of_birth'>Date of Birth:</label><input type='text' id='tb_date_of_birth' name='tb_date_of_birth' value='".$tb_date_of_birth."' /></td></tr>\n";
  echo "<tr><td><input type='button' id='btn_submit' name='btn_submit' value='Submit' /></td></tr>\n";
  echo "</table>\n";
  echo "<input type='hidden' id='hf_first_name' name='hf_first_name' value='' />\n";
  echo "<input type='hidden' id='hf_last_name' name='hf_last_name' value='' />\n";
  echo "<input type='hidden' id='hf_username' name='hf_username' value='' />\n";
  echo "<input type='hidden' id='hf_password' name='hf_password' value='' />\n";
  echo "<input type='hidden' id='hf_email' name='hf_email' value='' />\n";
  echo "<input type='hidden' id='hf_gender' name='hf_gender' value='' />\n";
  echo "<input type='hidden' id='hf_date_of_birth' name='hf_date_of_birth' value='' />\n";
  echo "</form>\n";
?>
</body>
</html>