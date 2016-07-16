<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
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
    $("#tbl_affiliates th.col").on("click", function() {
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
      $("#btn_delete").on("click", function() {
      clearHiddenFields();
      $("#hf_delete").val("delete");
      $("#form1").submit();
    });
    function clearHiddenFields() {
      $("#hf_delete").val("");
      $("#hf_search").val("");
    }
  });
</script>
<style type='text/css'>
  #tbl_affiliates th {
    padding-right:15px;
    background: url('images/icon_down_sort_arrow.png') no-repeat center right;
  }
  #tbl_affiliates th.asc {
    background: url('images/icon_up_sort_arrow.png') no-repeat center right;
  }
  .autocomplete-suggestions {
    border: 1px solid black;
  }
</style>
</head>
<body>
<?php
  $search = "";
  if (isset($_POST["hf_search"]) && $_POST["hf_search"] != "") {
    $search = $_POST["hf_search"];
  }
  $sort = "";
  if (isset($_POST["hf_sort"]) && $_POST["hf_sort"] != "") {
    $sort = $_POST["hf_sort"];
  }
  if (isset($_POST["hf_delete"]) && $_POST["hf_delete"] != "") {
    DBLogin("a8823305_audio"); 
    DBQuery("DELETE FROM affiliates WHERE id = ".$_POST["hf_delete"]);
    DBC();
  }
  if (logged_in()) {
    echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    echo "<form id='form1' name='form1' action='affiliates.php' method='post'>\n";
    echo "<table id='tbl_search'>\n";
    echo "<tr><td>Search:</td><td><input type='text' id='tb_search' name='tb_search' value='".($search != "" ? $search : "Search Keywords")."'></input></td>".
         "<td><input type='button' id='btn_search' name='btn_search' value='Search'></input></td></tr>\n";
    echo "</table>\n";
    echo "<table id='tbl_affiliates' border='1'>\n";
    $direction = "";
    if ($sort != "") {
      $length = strpos($sort, " ");
      $temp = $sort;
      $sort = substr($sort, 0, $length);
      $direction = substr($temp, $length+1);
      if ($direction == "ASC") {
        $direction = " asc";
      } else {
        $direction = "";
      }
    }
    echo "<tr><th class='".($sort == "id" ? $direction : "")."'>id</th><th class='col".($sort == "name" ? $direction : "")."'>name</th><th class='col".($sort == "relationship" ? $direction : "")."'>relationship</th><th clas='col".($sort == "websiteUrl" ? $direction : "")."'>websiteUrl</th><th class='col".($sort == "created" ? $direction : "")."'>created</th><th></th></tr>\n";
    $affiliates = getAffiliates($sort);
    foreach ($affiliates as $affiliate) {
      $id = $affiliate["id"];
      $name = (is_null($affiliate["name"]) ? "" : $affiliate["name"]);
      $relationship = (is_null($affiliate["relationship"]) ? "" : $affiliate["relationship"]);
      $websiteUrl = (is_null($affiliate["websiteUrl"]) ? "" : $affiliate["websiteUrl"]);
      $created = (is_null($affiliate["created"]) ? new DateTime("0000-00-00 00:00:00") : new DateTime($affiliate["created"]));
      if ($search == "" || ((strpos($name, $search) != -1) || (strpos($relationship, $search) != -1) || (strpos($websiteUrl, $search) != -1) || (strpos($created, $search) != -1)) {
        echo "<tr><td>".$id."</td><td>".$name."</td><td>".$relationship."</td><td>".$websiteUrl."</td><td>".$created."</td><td><input type='button' id='btn_delete' name='btn_delete' value='Delete' /></td></tr>\n";
      }
    }
    echo "</table>\n";
    echo "<input type='hidden' id='hf_delete' name='hf_delete' value='' />\n";
    echo "<input type='hidden' id='hf_search' name='hf_search' value='' />\n";
    echo "<input type='hidden' id='hf_sort' name='hf_sort' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getAffiliates($sort) {
    $affiliates = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM affiliates");
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $affiliates[] = $row;
    }
    DBC();
    return $affiliates;
  }
?>
</body>
</html>