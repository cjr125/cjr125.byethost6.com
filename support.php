<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $error = "";
  $request = "";
  if (isset($_POST["hf_reqeust"]) && $_POST["hf_request"] != "") {
    $request = $_POST["hf_request"];
  }
  if (isset($_POST["btn_submit"])) {
    if ($request != "") {
      mail("croberts1@gmail.com", "cjr125.netai.net support request", $request);
    }
  }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_submit").on("click", function() {
      $("#hf_request").val($("#tb_request").val());
      $("#form1").submit();
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
    echo "<form id='form1' name='form1' action='support.php' method='post'>\n";
    echo "<table id='tbl_support'>\n";
    echo "<tr><th>problem</th><th>result</th><th>solution</th></tr>\n";
    $supportExamples = getSupportExamples();
    foreach ($supportExamples as $supportExample) {
      $problem = $supportExample["problem"];
      $result = $supportExample["result"];
      $solution = $supportExample["solution"];
      echo "<tr><td>".$problem."</td><td>".$result."</td><td>".$solution."</td></tr>\n";
    }
    echo "<tr><td><textarea id='tb_request' name='tb_request'></textarea></td><td><input type='button' id='btn_submit' name='btn_submit' value='Submit' /></td><td></td></tr>\n";
    echo "</table>\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getSupportExamples() {
    $supportExamples = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM support");
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $supportExamples[] = $row;
    }
    DBC();
    return $supportExamples;
  }
?>
</body>
</html>