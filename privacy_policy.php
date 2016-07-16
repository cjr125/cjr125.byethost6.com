<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  if (isset($_POST["hf_agreed"]) && $_POST["hf_agreed"] != "") {
    $agreed = $_POST["hf_agreed"];
    if ($agreed) {
      header("Location: directory.php");
    } else {
      header("Location: cms.php");
    }
  }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_agree").on("click", function() {
      clearHiddenFields();
      $("#hf_agreed").val("agreed");
      $("#form1").submit();
    });
    $("#btn_disagree").on("click", function() {
      clearHiddenFields();
      $("#hf_agreed").val("disagreed");
      $("#form1").submit();
    });
    function clearHiddenFields() {
      $("#hf_agreed").val("");
    }
  });
</script>
</head>
<body>
<?php
  if (logged_in()) {
    echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    echo "<form id='form1' name='form1' action='privacy_policy.php' method='post'>\n";
    echo "<table id='tbl_privacy_policy'>\n";
    $headings = getHeadings();
    echo "<tr>";
    foreach ($headings as $heading) {
      echo "<th>".$heading."</th>";
    }
    echo "<th>Modified: ".$date_modified."</th>";
    echo "</tr>\n";
    $clauses = getClauses();
    echo "<tr>";
    foreach ($clauses as $clause) {
      echo "<td>".$clause."</td>";
    }
    echo "</tr>\n";
    echo "<tr><td></td><td><input type='button' id='btn_agree' name='btn_agree' value='Agree' /></td>";
    echo "<td><input type='button' id='btn_disagree' name='btn_disagree' value='Disagree' /></td><td></td></tr>\n";
    echo "</table>\n";
    echo "<input type='hidden' id='hf_agreed' name='hf_agreed' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getHeadings() {
    $result = DBQuery("SELECT headings FROM privacy_policy");
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    DBC();
    return explode(",", $result["headings"]);
  }
  function getClauses() {
    $result = DBQuery("SELECT clauses FROM privacy_policy");
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    DBC();
    return explode(";", $result["clauses"]);
  }
  function getReferences() {
    $result = DBQuery("SELECT references FROM privacy_policy");
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    DBC();
    return $result["references"];
  }
  function getDateModified() {
    $result = DBQuery("SELECT modified FROM privacy_policy");
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    DBC();
    return new DateTime($result["modified"]);
  }
?>
</body>
</html>