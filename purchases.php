<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $error = "";
?>
<script type="text/javscript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_display_report").on("click", function() {
      $("#hf_start_date").val($("#tb_start_date").val());
      $("#hf_end_date").val($("#tb_end_date").val());
      $("#form1").submit();
    });
    $("#tb_start_date").change(function() {
      $("#hf_start_date").val($("#tb_start_date").val());
      $("#form1").submit();
    });
    $("#tb_end_date").change(function() {
      $("#hf_end_date").val($("#tb_end_date").val());
      $("#form1").submit();
    });
  });
</script>
</head>
<body>
<?php
  include("class_calendar.php"); 
  $isPostBack = false;
  $referer = "";
  $thisPage = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
  if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = substr($_SERVER['HTTP_REFERER'],0,strpos($_SERVER['HTTP_REFERER'], "?"));
  }
  if ($referer == $thisPage) {
    $isPostBack = true;
  } 
  // Make objects 
  $Cal = array(new Calendar("caltest.php"), new Calendar("caltest.php"));    // set page-name 
  if (!$isPostBack) {
    $Cal[0]->Day = date("d");            // example - set current date 
    $Cal[0]->Month = date("m"); 
    $Cal[0]->Year = date("Y");
    $Cal[1]->Day = date("d");            // example - set current date 
    $Cal[1]->Month = date("m"); 
    $Cal[1]->Year = date("Y");
  }
  else {
    $Cal[0]->Day = $_GET['StartDateChosenDay'];
    $Cal[0]->Month = $_GET['StartDateChosenMonth'];
    $Cal[0]->Year = $_GET['StartDateChosenYear'];
    $Cal[1]->Day = $_GET['EndDateChosenDay'];
    $Cal[1]->Month = $_GET['EndDateChosenMonth'];
    $Cal[1]->Year = $_GET['EndDateChosenYear'];
  }
  if (logged_in()) {
    echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    if ($error != "") {
      echo "<span class='error'>".$error."</span><br>\n";
    }
    echo "<form id='form1' name='form1' action='purchases.php' method='post'>\n";
    echo "<table id='tbl_input_params'>\n";
    echo "<tr><th>start date</th><th>end date</th><th></th></tr>\n";
    echo "<tr><td>".$Cal[0]->Show()."</td><td>".$Cal[1]->Show()."</td><td><input type='button' id='btn_display_report' name='btn_display_report' value='Display Report' /></td></tr>\n";
    echo "</table>\n"
    echo "<table id='tbl_purchases'>\n";
    echo "<tr><th>buyer</th><th>product name</th><th>price</th></tr>\n";
    $purhases = getPurchases(new DateTime($_GET['StartDateChosenYear']."-".$_GET['StartDateChosenMonth']."-".$_GET['StartDateChosenDay']), new DateTime($_GET['EndDateChosenYear']."-".$_GET['EndDateChosenMonth']."-".$_GET['EndDateChosenDay']));
    foreach ($purchases as $purchase) {
      $buyer = $purchase["buyer"];
      $product_name = $purchase["buyer_name"];
      $price = $purchase["price"];
      echo "<tr><td>".$buyer."</td><td>".$product."</td><td>".$price."</td></tr>\n";
    }
    echo "</table>\n";
    echo "<input type='hidden' id='hf_start_date' name='hf_start_date' value='' />\n";
    echo "<input type='hidden' id='hf_end_date' name='hf_end_date' value='' />\n";
    echo "</form>\n";
  } else {
    header("Locations: cms.php");
  }
  function getPurchases($start_date, $end_date) {
    $purchases = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM purchases WHERE Date BETWEEN '".$start_date."' AND '".$end_date."'");
    if ($result->num_rows == 0) {
      echo "No rows found.";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $purchases[] = $row;
    }
    DBC();
    return $purchases;
  }
?>
</body>
</html>