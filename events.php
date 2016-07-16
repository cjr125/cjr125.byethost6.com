<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
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
        DBQuery("DELETE FROM events WHERE 1=1");
        DBC();
    }
    if (isset($_POST["hf_cms_view"]) && $_POST["hf_cms_view"] != "") {
        $mediaId = $_POST["hf_cms_view"];
        setcookie('mediaId', $mediaId);
        header("Location: cms.php");
    }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/jquery.autocomplete.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
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
    $("#tbl_events th.col").on("click", function() {
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
    $("#tbl_events .btn_cms_view").on("click", function() {
      clearHiddenFields();
      var rowIndex = $(".btn_cms_view").index(this);
      $("#hf_cms_view").val($("#tbl_events td#mediaId")[rowIndex].innerHTML);
      $("#form1").submit();
    });
    $("#btn_delete_all").on("click", function() {
      clearHiddenFields();
      $("#hf_delete").val("delete");
      $("#form1").submit();
    });
    function clearHiddenFields() {
      $("#hf_cms_view").val("");
      $("#hf_delete").val("");
      $("#hf_search").val("");
    }
  });
</script>
<style type='text/css'>
  #tbl_events th {
    padding-right:15px;
    background: url('images/icon_down_sort_arrow.png') no-repeat center right;
  }
  #tbl_events th.asc {
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
        DBQuery("DELETE FROM events WHERE 1=1");
        DBC();
    }
    if (isset($_POST["hf_cms_view"]) && $_POST["hf_cms_view"] != "") {
        $mediaId = $_POST["hf_cms_view"];
        setcookie('mediaId', $mediaId);
        header("Location: cms.php");
    }
    if (logged_in()) {
        echo "Logged in as ".$_COOKIE["user"]." <a href=\"logout.php\">Logout</a><br>\n";
        include 'menu_bar.php';
        echo "<form id='form1' name='form1' action='events.php' method='post'>\n";
        DBLogin("a8823305_audio");
        $SQL = "SELECT * FROM events";
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
        echo "<table id='tbl_events' border='1'>\n";
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
        echo "<tr><th class='col".($sort == "eventId" ? $direction : "")."'>eventId</th><th class='col".($sort == "mediaId" ? $direction : "")."'>mediaId</th><th class='col".($sort == "eventType" ? $direction : "")."'>eventType</th><th class='col".($sort == "created" ? $created : "")."'>created</th><th class='col".($sort == "eventDuration" ? $direction : "")."'>eventDuration</th><th><input type='submit' id='btn_delete_all' name='delete' value='Delete All'></input></th></tr>\n";
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $eventId = $row["eventId"];
            $mediaId = $row["mediaId"];
            $eventType = $row["eventType"];
            $eventDuration= $row["eventDuration"];
            if ($search == "" || ((strpos($eventType, $search) != -1) {
                echo "<tr><td id='eventId'>".$eventId."</td><td id='mediaId'>".$mediaId."</td><td>".$eventType."</td><td>".$created."</td><td>".$eventDuration."</td>".
                     "<td><input type='button' class='btn_cms_view' name='cms_view' value='View in CMS'></input></td></tr>\n";
            }
        }
        echo "</table>\n";
        echo "<input type='hidden' id='hf_cms_view' name='hf_cms_view' value='' />\n";
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