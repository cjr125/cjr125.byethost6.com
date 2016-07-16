<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $agreed = false;
  if (isset($_POST["hf_agreed"]) && $_POST["hf_agreed"] != "") {
    $agreed = $_POST["hf_agreed"];
    DBLogin("a8823305_audio");
    if ($agreed) {
      DBQuery("UPDATE users SET accountStatus = 'agreed'");
    } else {
      DBQuery("UPDATE users SET accountStatus = 'disagreed'");
    }
    DBC();
  }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_agree").on("click", function() {
      $("#hf_agreed").val("true");
      $("#form1").submit();
    });
    $("#btn_disagree").on("click", function() {
      $("#hf_agreed").val("false");
      $("#form1").submit();
    });
    function clearHiddenFields() {
      $("#hf_agreed").val("");
    }
  });
</script>
<style type="text/css">
  .agreement {
    margin:20px auto;
    width:750px;
  }
  .submitButtonContainer {
    margin:10px auto;
    width:300px;
    height:40px;
  }
  #btn_agreed {
    float:left;
  }
  #btn_disagree {
    float:right;
  }
</style>
</head>
<body>
<?php
  echo "<form id='form1' name='form1' action='agreement.php' method='post'>\n";
  echo "<h4>Terms and Conditions</h4>\n";
  echo "<div class='agreement'>\n";
  echo "<b>Read and agree to the following terms and conditions:</b>\n";
  echo "<div class='submitButtonContainer'><input type='button' id='btn_agreed' name='btn_agreed' value='Agree' /><input type='button' id='btn_disagree' name='btn_disagree' value='Disagree' /></div>\n";
  echo "</div>\n";
  echo "<input type='hidden' id='hf_agreed' name='hf_agreed' value='' />\n";
  echo "</form>\n";
?>
</body>
</html>