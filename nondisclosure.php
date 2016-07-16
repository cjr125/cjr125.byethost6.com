<!DOCTYPE html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $error = "";
  $client_id = $_GET["client_id"];
 
  if (isset($_POST["hf_agreement_status"]) && $_POST["hf_agreement_status"] != "") {
    $agreement_status = $_POST["hf_agreement_status"];
    if ($agreement_status == "agree") {
      header("Location: directory.php");
    }
    else {
      $_COOKIE["user"] = "";
      header("Location: cms.php");
    }
  }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_submit").on("click", function() {
      $("#hf_agreement_status").val($("#rb_agreement_status:selected").val());
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
    echo "<form id='form1' name='form1' action='nondisclosure.php' method='post'>\n";
    echo "<table id='tbl_nondisclosure'>\n";
    echo "<tr><th>section name</th><th>conditions</th></tr>\n";
    $nondisclosure_conditions = getNondisclosureConditions($client_id);
    foreach ($nondisclosure_conditions as $nondisclosure_condition) {
      $section_name = $nondisclosure_condition["section_name"];
      $conditions = $nondisclosure_condition["conditions"];
      echo "<tr><td>".$section_name."</td><td>".$conditions."</td></tr>\n";
    }
    echo "<tr><td><input type='radio' id='rb_agreement_status' name='rb_agreement_status' value='agree' />Agree<input type='radio' id='rb_agreement_status' name='rb_agreement_status' value='disagree'>Disagree</td>\n";
    echo "<td><input type='button' id='btn_submit' name='btn_submit' value='Submit' /></td></tr>\n";
    echo "</table>\n";
    echo "<input type='hidden' id='hf_agreement_status' name='hf_agreement_status' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getNondisclosureConditions($client_id) {
    $nondisclosure_conditions = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM nondisclosure_conditions WHERE client_id = ".$client_id);
    if ($result->num_rows == 0) {
      echo "No rows found.";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $nondisclosure_conditions[] = $row;
    }
    DBC();
    return $nondisclosure_conditions;
  }
?>
</body>
</html>