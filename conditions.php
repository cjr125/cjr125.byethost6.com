<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_agree").on("click", function() {
      $("#hf_agreed").val("agreed");
    });
    $("#btn_disagree").on("click, function() {
      $("#hf_agreed").val("disagree");
    });
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
    $("#tbl_conditions th.col").on("click", function() {
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
    $("#btn_delete").on("click", function() {
      clearHiddenFields();
      $("#hf_delete").val("delete");
      $("#form1").submit();
    });
    function clearHiddenFields() {
      $("#hf_delete").val("");
      $("#hf_search").val("");
      $("#hf_agreed").val("");
    }
  }
</script>
<style type='text/css'>
  #tbl_conditions th {
    padding-right:15px;
    background: url('images/icon_down_sort_arrow.png') no-repeat center right;
  }
  #tbl_conditions th.asc {
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
  if (isset($_POST["hf_delete"]) && $_POST["hf_delete"] == "delete") {
      DBLogin("a8823305_audio"); 
      DBQuery("DELETE FROM conditions WHERE 1=1");
      DBC();
  }
  if (logged_in()) {
    echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    echo "<form id='form1' name='form1' action='conditions.php' method='post'>\n";
    DBLogin("a8823305_audio");
    $SQL = "SELECT * FROM conditions";
    if ($sort != "") {
      $SQL .= " ORDER BY ".$sort;
    }
    $result = DBQuery($SQL);
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    echo "<table id='tbl_search'>\n";
    echo "<tr><td>Search:</td><td><input type='text' id='tb_search' name='tb_search' value='".($search != "" ? $search : "Search Keywords")."'></input></td>".
         "<td><input type='button' id='btn_search' name='btn_search' value='Search'></input></td></tr>\n";
    echo "</table>\n";
    echo "<table id='tbl_conditions'>\n";
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
    echo "<tr><th class='col".($sort == "id" ? $direction : "")."'>id</th><th class='col".($sort == "heading" ? $direction : "")."'>heading</th><th class='".($sort == "paragraph" ? $direction : "")."'>paragraph</th><th class='col".($sort == "created" ? $direction : "")."'>created</th><th class='col".($sort == "modified" ? $direction : "")."'>modified</th></tr>\n";
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
      $id = $row["id"];
      $heading = (is_null($row["heading"]) ? "" : $row["heading"]);
      $paragraph = (is_null($row["paragraph"]) ? "" : $row["paragraph"]);
      $created = (is_null($row["created"]) ? new DateTime("0000-00-00 00:00:00") : new DateTime($row["created"]));
      $modified = (is_null($row["modified"] ? new DateTime("0000-00-00 00:00:00") : new DateTime($row["modified"]));
      if ($search == "" || ((strpos($heading, $search) != -1) || (strpos($paragraph, $search) != -1) || (strpos($created, $search) != -1) || (strpos($modified, $search) != -1)) {
        echo "<tr><td>".$id."</td><td>".$heading."</td><td>".$paragraph."</td><td>".$created."</td><td>".$modified."</td></tr>\n";
      }
    }
    echo "<tr><td></td><td><input type='button' id='btn_agree' name='btn_agree' value='Agree' /></td><td><input type='button' id='btn_disagree' name='btn_disagree' value='Disagree' /></td><td><input type='button' id='btn_delete' name='btn_delete' value='Delte' /></td></tr>\n";
    echo "</table>\n";
    echo "<input type='hidden' id='hf_agreed' name='hf_agreed' value='' />\n";
    echo "<input type='hidden' id='hf_delete' name='hf_delete' value='' />\n";
    echo "<input type='hidden' id='hf_search' name='hf_search' value='' />\n";
    echo "<input type='hidden' id='hf_sort' name='hf_sort' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
?>
</body>
</html>