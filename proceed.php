<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $email = "";

  if (isset($_COOKIE["page"]) && $_COOKIE["page"] != "proceed.php") {
    header("Location: ".$_COOKIE["page"]);
  }
  setcookie('page', 'proceed.php');

  DBLogin("a8823305_audio");
  $result = DBQuery("SELECT email FROM users WHERE username = '".$user."'");
  if ($result->num_rows > 0) {
    $result = $result->fetch_array(MYSQL_ASSOC);
    $email = $result["email"];
  }

  if (isset($_POST["hf_cancel"]) && $_POST["hf_cancel"] != "") {
    DBLogin("a8823305_audio");
    DBQuery("DELETE FROM users WHERE username = '".$user."'");
    DBC();
  } else {
    header("Location: workstation.php");
  }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_proceed").on("click", function() {
      clearHiddenFields();
      $("#form1").submit();
    });
    $("#btn_cancel").on("click", function() {
      $("#hf_cancel").val("cancel");
      $("#form1").submit();
    });
    function clearHiddenFields() {
      $("#hf_cancel").val("");
    }
  });
</script>
<link rel="stylesheet" type="text/css" href="css/common.css" />
</head>
<body>
<?php
  if (logged_in()) {
    echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    echo "<form id='form1' name='form1' action='proceed.php'>\n";
    echo "<div class='instructions'>To prove you are the owner of ".$email.", please complete the following reCAPTCHA and click proceed.</div>\n";
    require_once('recaptchalib.php');

    // Get a key from https://www.google.com/recaptcha/admin/create
    $publickey = "";
    $privatekey = "";

    # the response from reCAPTCHA
    $resp = null;
    # the error code from reCAPTCHA, if any
    $error = null;

    # was there a reCAPTCHA response?
    if ($_POST["recaptcha_response_field"]) {
      $resp = recaptcha_check_answer ($privatekey,
                                      $_SERVER["REMOTE_ADDR"],
                                      $_POST["recaptcha_challenge_field"],
                                      $_POST["recaptcha_response_field"]);

      if ($resp->is_valid) {
        echo "You got it!";
      } else {
        # set the error code so that we can display it
        $error = $resp->error;
      }
    }
    echo recaptcha_get_html($publickey, $error);
    echo "<br><input type='button' id='btn_proceed' name='btn_proceed' value='Proceed' />\n";
    echo "<br><input type='button' id='btn_cancel' name='btn_cancel' value='Cancel' />\n";
    echo "<input type='hidden' id='hf_cancel' name='hf_cancel' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
?>
</body>
</html>