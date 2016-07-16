<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  require 'HtmlUtil.php';

  $user = $_COOKIE["user"];
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#tb_search").on("click", function() {
      if ($("#tb_search").val() == "Search Keywords") {
        $("#tb_search").val("");
      }
    });
    $("#tb_search").autocomplete({
      serviceUrl:'getautocomplete.php',
      minLength:1
    });
    $("#btn_search").on("click", function() {
      clearHiddenFields();
      $("#hf_search").val($("#tb_search").val());
      $("#form1").submit();
    });
    $("#tbl_effects th.col").on("click", function() {
      clearHiddenFields();
      var direction = "DESC";
      if (this.classList.contains("asc")) {
        this.classList.remove("asc");
      } else {
        direction = "ASC";
        this.classList.add("asc");
      }
      $("#hf_sort").val(this.innerHTML + " " + direction);
      $("#form1").submit();
    });
  });
</script>
</head>
<body>
<?php
  if (logged_in()) {
    echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    echo "<form id='form1' name='form1' action='effects.php' method='post'>\n";
    echo "<table id='tbl_effects'>\n";
    echo "<tr><th class='col".($sort == "id" ? $direction : "")."'>id</th><th class='col".($sort == "name" ? $direction : "")."'>name</th><th class='col".($sort == "effect" ? $direction : "")."'>effect</th><th class='col".($sort == "description" ? $direction : "")."'>description</th></tr>\n";
    $effects = getEffects($sort);
    foreach ($effects as $effect) {
      $id = $effect["id"];
      $name = (is_null($effect["name"]) ? "" : $effect["name"]);
      $source = (is_null($effect["source"]) ? "" : $effect["source"]);
      $imageUrl = (is_null($effect["imageUrl"]) ? "" : $effect["imageUrl"]);
      $description = (is_null($effect["description"]) ? "" : $effect["description"]);
      echo "<tr><td class='effectId'>".$id."</td><td>".$name."</td><td>".$source."</td><td>".$description."</td></tr>\n";
    }
    echo "</table>\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getEffects($sort) {
    $effects = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM effects");
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $effects[] = $row;
    }
    DBC();
    return $effects;
  }
?>
?>
</body>
</html>