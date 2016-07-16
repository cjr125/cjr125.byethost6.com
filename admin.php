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
    $("#hf_start_date").val(getUrlVars()["ChosenYearStart"] + "/" + getUrlVars()["ChosenMonthStart"] + "/" + getUrlVars()["ChosenDayStart"]);
      $("#hf_end_date").val(getUrlVars()["ChosenYearEnd"] + "/" + getUrlVars()["ChosenMonthEnd"] + "/" + getUrlVars()["ChosenDayEnd"]);
      $("#form1").submit();
    });
    $("#tb_start_date").change(function() {
      $("#hf_start_date").val(getUrlVars()["ChosenYearStart"] + "/" + getUrlVars()["ChosenMonthStart"] + "/" + getUrlVars()["ChosenDayStart"]);
      $("#form1").submit();
    });
    $("#tb_end_date").change(function() {
      $("#hf_end_date").val(getUrlVars()["ChosenYearEnd"] + "/" + getUrlVars()["ChosenMonthEnd"] + "/" + getUrlVars()["ChosenDayEnd"]);
      $("#form1").submit();
    });
    function getUrlVars() {
      var vars = [], hash;
      var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
      for(var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
      }
      return vars;
    }
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

  $Cal = array(new Calendar("conversion_report.php"), new Calendar("conversion_report.php"));

  if (!$isPostBack) {
    $Cal[0]->Day = date("d");
    $Cal[0]->Month = date("m"); 
    $Cal[0]->Year = date("Y");
    $Cal[1]->Day = date("d");
    $Cal[1]->Month = date("m"); 
    $Cal[1]->Year = date("Y");
  }
  else {
    $Cal[0]->Day = $_GET['ChosenDay'];
    $Cal[0]->Month = $_GET['ChosenMonth'];
    $Cal[0]->Year = $_GET['ChosenYear'];
    $Cal[1]->Day = $_GET['ChosenDay'];
    $Cal[1]->Month = $_GET['ChosenMonth'];
    $Cal[1]->Year = $_GET['ChosenYear'];
  }
  if (logged_in()) {
    echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    echo "<form id='form1' name='form1' action='admin.php' method='post'>\n";
    echo "<table id='tbl_admin'>\n";
    echo "<tr><th>page title</th><th>content spot name</th><th>impressions</th><th>clicks</th><th>conversion</th></tr>\n";
    $admin = getAdmin($client_id);
    foreach ($admin as $admin_row) {
      $page_title = $admin_row["page_title"];
      $content_spot_name = $admin_row["content_spot_name"];
      $impressions = $admin_row["impressions"];
      $clicks = $admin_row["clicks"];
      $conversion = $admin_row["conversion"];
      echo "<tr><td>".$page_title."</td><td>".$content_spot_name."</td><td>".$impressions."</td><td>".$clicks."</td><td>".$conversion."</td></tr>\n";
    }
    echo "<tr><td colspan='4'></td><td><input type='button' id='btn_submit' name='btn_submit' value='Submit' /></td></tr>\n";
    echo "</table>\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getAdmin($client_id) {
    $admin = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM admin WHERE client_id = ".$client_id);
    if ($result->num_rows == 0) {
      echo "No rows found.";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $admin[] = $row;
    }
    DBC();
    return $admin;
  }
?>
</body>
</html>