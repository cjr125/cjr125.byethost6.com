<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';

  $user = $_COOKIE["user"];
  $error = "";
  $name = "";
  $purchases = "";
  $quantities = "";
  $total = 0.0;

  if (isset($_POST["hf_delete"])) {
  }
  if (isset($_POST["hf_name"]) && $_POST["hf_name"]) {
    $name = $_POST["hf_name"];
  }
  if (isset($_POST["hf_purchases"]) && $_POST["hf_purchases"]) {
    $purchases = $_POST["hf_purchases"];
  }
  if (isset($_POST["hf_quantities"]) && $_POST["hf_quantities"]) {
    $quantities = $_POST["hf_quantities"];
  }
  if (isset($_POST["btn_submit"])) {
    if ($quantities != "" && $purchases != "" && $name != "") {
      DBLogin("b6_17569910_audio");
      DBQuery("INSERT INTO customers (name,purchases,quantities,total) VALUES ('".$name."','".$purchases."','".$quantities."',$total)");
      DBC();
    } else {
      $error = "Invalid arguments.";
    }
  }
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
    $("#tbl_customers" th.col").on("click", function() {
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
    $("#btn_submit").on("click", function() {
      clearHiddenFields();
      $("#hf_name").val($("#tb_name").val());
      $("#hf_purchases").val("#tb_purchases").val());
      $("#hf_quantities").val("#tb_quantities").val());
      $("#form1").submit();
    });
    function clearHiddenFields() {
      $("#hf_name").val("");
      $("#hf_purchases").val("");
      $("#hf_quantities").val("");
      $("#hf_delete").val("");
      $("#hf_search").val("");
      $("#hf_sort").val("");
    }
  });
</script>
<style type='text/css'>
  #tbl_customers th {
    padding-right:15px;
    background: url('images/icon_down_sort_arrow.png') no-repeat center right;
  }
  #tbl_customers th.asc {
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
    DBLogin("b6_17569910_audio"); 
    DBQuery("DELETE FROM customers WHERE 1=1");
    DBC();
  }
  if (logged_in()) {
        echo "Logged in as ".$_COOKIE["user"]." <a href=\"logout.php\">Logout</a><br>\n";
        include 'menu_bar.php';
        if ($error != "") {
            echo "<span class='error'>".$error."</span><br/>\n";
        }
        echo "<form id='form1' name='form1' action='customers.php' method='post'>\n";
        DBLogin("b6_17569910_audio");
        $SQL = "SELECT * FROM customers";
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
        echo "<table id='tbl_customers'>\n";
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
        echo "<tr><th class='col".($sort == "id" ? $direction : "")."'>id</th><th class='col".($sort == "name" ? $direction : "")."'>name</th><th class='col".($sort == "purchases" ? $direction : "")."'>purchases</th><th class='col".($sort == "quantities" ? $direction : "")."'>quantities</th><th class='col".($sort == "total" ? $direction : "")."'>total</th><th></th></tr>\n";
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
          $id = $row["id"];
          $name = (is_null($row["name"]) ? $name : $row["name"]);
          $purchases = (is_null($row["purchases"]) ? $purchases : $row["purchases"]);
          $quantities = (is_null($row["quantities"]) ? $quantities : $row["quantities"]);
          $total = (is_null($row["total"]) ? $total : $row["total"]);
          if ($search == "" || strpos($name, $search) != -1 || strpos($purchases, $search) != -1 || strpos($quantities, $search) != -1 || intval($search) != $total) {
            echo "<tr><td>".$name."</td><td>".$purchases."</td><td>".$quantities."</td><td>".$total."</td><td><input type='button' id='btn_delete' name='btn_delete' value='Delete' /></td></tr>\n";
          }
        }
        echo "</table>\n";
        echo "<input type='hidden' id='hf_name' name='hf_name' value='' />\n";
        echo "<input type='hidden' id='hf_purchases' name='hf_purchases' value='' />\n";
        echo "<input type='hidden' id='hf_quantities' name='hf_quantities' value='' />\n";
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