<!DOCTYPE html>
<html>
<head>
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
    $("#tbl_content_spots th.col").on("click", function() {
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
    $("#tb_position").change(function() {
      clearHiddenFields();
      var rowIndex = $("#tb_position").index(this);
      var position = parseInt($("#tb_position").val());
      $("#hf_position").val(rowIndex + " " + position);
      $("#form1").submit();
    });
    $("#active").change(function() {
      clearHiddenFields();
      var rowIndex = $("#active").index(this);
      if ($(this).is(":checked") {
        $("#hf_active").val($("#tbl_content_spots td#content_spot_id")[rowIndex].innerHTML + " 1");
      } else {
        $("#hf_active").val($("#tbl_content_spots td#content_spot_id")[rowIndex].innerHTML + " 0");
      }
      $("#form1").submit();
    });
    $("#tbl_content_spots .btn_cms_view").on("click", function() {
      clearHiddenFields();
      var rowIndex = $(".btn_cms_view").index(this);
      $("#hf_cms_view").val($("#tbl_content_spots td#mediaId")[rowIndex].innerHTML);
      $("#form1").submit();
    });
    $("#btn_delete").on("click", function() {
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
  #tbl_content_spots th {
    padding-right:15px;
    background: url('images/icon_down_sort_arrow.png') no-repeat center right;
  }
  #tbl_content_spots th.asc {
    background: url('images/icon_up_sort_arrow.png') no-repeat center right;
  }
  .autocomplete-suggestions {
    border: 1px solid black;
  }
</style>
</head>
<body>
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
        DBQuery("DELETE FROM content_spots WHERE 1=1");
        DBC();
    }
    if (isset($_POST["hf_active"]) && $_POST["hf_active"] != "") {
        $active_status = strsplit($_POST["hf_active"]);
        if (count($active_status) == 2) {
            $content_spot_id = $active_status[0];
            $active = $active_status[1];
            DBLogin("a8823305_audio");
            DBQuery("UPDATE content_spots SET active = ".$active." WHERE content_spot_id = ".$content_spot_id);
            DBC();
        }
    }
    $position = "";
    if (isset($_POST["hf_position"]) && $_POST["hf_position"] != "") {
        $position_status = strsplit($_POST["hf_position"]);
        if (count($position_status == 2) {
            $content_spot_id = $position_status[0];
            $position = intval($position_status[1]);
            if ($position > -1) {
                DBLogin("a8823305_audio");
                DBQuery("UPDATE content_spots SET position = ".$position." WHERE content_spot_id = ".$content_spot_id);
                DBC();
            }
        }
    }
    if (isset($_POST["hf_cms_view"]) && $_POST["hf_cms_view"] != "") {
        $mediaId = $_POST["hf_cms_view"];
        setcookie('mediaId', $mediaId);
        header("Location: cms.php");
    }
    if (logged_in()) {
        echo "Logged in as ".$_COOKIE["user"]." <a href=\"logout.php\">Logout</a><br>\n";
        include 'menu_bar.php';
        echo "<form id='form1' name='form1' action='contentSpots.php' method='post'>\n";
        DBLogin("a8823305_audio");
        $SQL = "SELECT * FROM content_spots";
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
        echo "<table id='tbl_content_spots' border='1'>\n";
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
        echo "<tr><th class='col".($sort == "content_spot_id" ? $direction : "")."'>content_spot_id</th><th class='col".($sort == "mediaId" ? $direction : "")."'>mediaId</th><th class='col".($sort == "content_spot_name" ? $direction : "")."'>content_spot_name</th><th class='col".($sort == "content_spot_url" ? $direction : "")."'>content_spot_url</th><th class='col".($sort == "content_spot_category" ? $direction : "")."'>content_spot_category</th><th class='col".($sort == "width" ? $direction : "")."'>width</th><th class='col".($sort == "height" ? $direction : "")."'>height</th><th class='col".($sort == "position" ? $direction : "")."'>position</th><th class='col".($sort == "rotation" ? $direction : "")."'>rotation</th><th class='col".($sort == "rotationCapacity" ? $direction : "")."'>rotation capacity</th><th class='col".($sort == "active" ? $direction : "")."'>active</th><th><input type='submit' id='btn_delete' name='delete' value='Delete All'></input></th></tr>\n";
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $content_spot_id = $row["content_spot_id"];
            $mediaId = $row["mediaId"];
            $content_spot_name = $row["content_spot_name"];
            $content_spot_url = $row["content_spot_url"];
            $content_spot_category = $row["content_spot_category"];
            $width = $row["width"];
            $height = $row["height"];
            $position = $row["position"];
            $rotation = $row["rotation"];
            $active = $row["active"];
            if ($search == "" || ((strpos($content_spot_name, $search) != -1 || strpos($content_spot_url, $search) != -1 || strpos($content_spot_category, $search) != -1) {
                echo "<tr><td id='content_spot_id'>".$content_spot_id."</td><td id='mediaId'>".$mediaId."</td><td>".$content_spot_name."</td><td>".$content_spot_url."</td>".
                     "<td>".$content_spot_category."</td><td>".$width."</td><td>".$height."</td><td><input type='text' id='tb_position' name='tb_position' value='".$position."' /></td><td>".$rotation."</td><td>".$rotationCapacity."</td><td><input type='checkbox' id='active' name='active' style='vertical-align:bottom' ".($active ? "checked" : "")."/><td><input type='button' class='btn_cms_view' name='cms_view' value='View in CMS'></input></td></tr>\n";
            }
        }
        echo "</table>\n";
        echo "<input type='hidden' id='hf_active' name='hf_active' value='' />\n";
        echo "<input type='hidden' id='hf_position' name='hf_position' value='' />\n";
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