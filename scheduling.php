<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $id = -1;
  $schedule = "";
  $categories = "";
  $selected_categories = "";
  $keywords = "";
  $selected_keywords = "";
  $startDate = new DateTime("NOW");
  $length = strtotime("0000-00-00 00:00:00");
  $created = new DateTime("NOW");
  if (isset($_POST["hf_schedule"]) && $_POST["hf_schedule"] != "") {
    $schedule = $_POST["hf_schedule"];
  }
  if (isset($_POST["hf_categories"]) && $_POST["hf_categories"] != "") {
    $categories = $_POST["hf_categories"];
  }
  if (isset($_POST["hf_keywords"]) && $_POST["hf_keywords"] != "") {
    $keywords = $_POST["hf_keywords"];
  }
  $tb_category = "Add Category";
  else if ($_POST["hf_update"] == "update_categories" && $id > -1) {
    DBLogin("a8823305_audio");
    if ($tb_category != "" && $tb_category != "Add Category") {
      $selected_categories .= ($selected_categories == "" ? $tb_categories : ",".$tb_category);
      DBQuery("UPDATE schedule SET categories = '".$selected_categories."' WHERE id = ".$id);
    }
    $selected_categories_array = explode(",",$selected_categories);
    $selection = "";
    $foreach ($selected_categories_array as $category) {
      if ($category != "" && strpos($schedule,$category) != -1) {
        $selection .= ($selection == "" ? $category : ",".$category);
      }
    }
    if ($selection != "") {
      DBQuery("UPDATE schedule SET categories = '".$selection."' WHERE id = ".$id);
    }
    $result = DBQuery("SELECT categories FROM schedule WHERE id = ".$id);
    if ($result->num_rows > 0) {
      $result = $result->fetch_row();
      $categories = $result["categories"];
    }
    if ($categories && $categories != "") {
      $categories = createDropdown(",",$categories,"dd_categories",$selected_categories);
      setcookie("selected_categories",$selected_categories);
    }
    DBC();
  }
  $tb_keyword = "Add Keyword";
  else if ($_POST["hf_update"] == "update_keywords" && $id > -1) {
    DBLogin("a8823305_audio");
    if ($tb_keyword != "" && $tb_keyword != "Add Keyword") {
      $selected_keywords .= ($selected_keywords == "" ? $tb_keyword : ",".$tb_keyword);
      DBQuery("UPDATE schedule SET keywords = '".$selected_keywords."' WHERE id = ".$id);
    }
    $selected_keywords_array = explode(",",$selected_keywords);
    $selection = "";
    foreach ($selected_keywords_array as $keyword) {
      if ($keyword != "" && strpos($schedule,$keyword) != -1) {
        $selection .= ($selection == "" ? $keyword : ",".$keyword);
      }
    }
    if ($selection != "") {
      DBQuery("UPDATE schedule SET keywords = '".$selection."' WHERE id = ".$id);
    }
    $result = DBQuery("SELECT keywords FROM schedule WHERE id = ".$id);
    if ($result->num_rows > 0) {
      $result = $result->fetch_row();
      $keywords = $result["keywords"];
    }
    if ($keywords && $keywords != "") {
      $keywords = createDropdown(",",$keywords,"dd_keywords",$selected_keywords);
      setcookie("selected_keywords",$selected_keywords);
    }
    DBC();
  }
  if (isset($_POST["hf_start_date"]) && $_POST["hf_start_date"] != "") {
    $startDate = $_POST["hf_start_date"];
  }
  if (isset($_POST["hf_length"]) && $_POST["hf_length"] != "") {
    $length = $_POST["hf_length"];
  }
  if (isset($_POST["btn_submit"])) {
    if ($length != strtotime("0000-00-00 00:00:00") && $keywords != "" && $categories != ""  && $schedule != "") {
      DBLogin("a8823305_audio");
      $result = DBQuery("INSERT INTO schedules (schedule,categories,keywords,startDate,length,created) VALUES ('".$schedule."','".$categories."','".$keywords"','".$startDate."','".$length."','".$created"')");
      if ($result) {
        $id = $result->insert_id();
        setcookie("mediaId",$id);
      }
      DBC();
    }
  }
  if ($_COOKIE["selected_categories"] && $_COOKIE["selected_categories"] != "") {
    $selected_categories = html_entity_decode($_COOKIE["selected_categories"]);
  }
  if ($_COOKIE["selected_keywords"] && $_COOKIE["selected_keywords"] != "") {
    $selected_keywords = html_entity_decode($_COOKIE["selected_keywords"]);
  }
  if (isset($_COOKIE['mediaId']) && $_COOKIE['mediaId'] != "") {
    $id = intval($_COOKIE['mediaId']);
    if ($id > -1) {
      DBLogin("a8823305_audio");
      $result = DBQuery("SELECT * FROM schedules WHERE id = ".$id);
      if ($result->num_rows > 0) {
        while($row = $result->fetch_row()) {
          $schedule = (is_null($row["schedule"]) ? $schedule : $row["schedule"]);
          $categories = (is_null($row["categories"]) ? $categories : $row["categories"]);
          $categories = createDropdown(",",$categories,"dd_categories",$seleted_categories);
          $keywords = (is_null($row["keywords"]) ? $keywords : $row["keywords"]);
          $keywords = createDropdodwn(",",$keywords,"dd_keywords",$selected_keywords);
          $startDate = (is_null($row["startDate"]) ? $startDate : $row["startDate"]);
          $length = (is_null($row["length"]) ? $length : $row["length"]);
        }
      }
      DBC();
    }
  }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
    $("#dd_categories").change(function() {
      var selected_categories = document.cookie["selected_categories"];
      if (selected_categories) {
        selected_categories += (selected_categories != "" ? "," : "") + $("#dd_categories:selected").val();
        $("#schedule").val($("#schedule").val().replace("/[category]/g", selected_categories));
      }
      document.cookie["selected_categories"] = selected_categories;
      $("#hf_update").val("update_categories");
    });
    $("#dd_keywords").change(function() {
      var selected_keywords = document.cookie["selected_keywords"];
      if (selected_keywords) {
        selected_keywords += (selected_keywords != "" ? "," : "") + $("#dd_keywords:selected").val();
        $("#schedule").val($("#schedule").val().replace("/[keyword]/g", selected_keywords));
      }
      document.cookie["selected_keywords"] = selected_keywords;
      $("#hf_update").val("update_keywords");
    }
    $("#btn_submit").on("click", function() {
      clearHiddenFields();
      $("#hf_schedule").val($("#schedule").val());
      $("#hf_categories").val($("#tb_category").val());
      $("#hf_keywords").val($("#tb_keyword").val());
      $("#hf_start_date").val($("#tb_start_date").val());
      $("#hf_length").val($("#tb_length").val());
      $("#form1").submit();
    });
    function clearHiddenFields() {
      $("#hf_schedule").val("");
      $("#hf_categories").val("");
      $("#hf_keywords").val("");
      $("#hf_start_date").val("");
      $("#hf_length").val("");
    }
  });
</script>
<body>
  <?php
   if (logged_in()) {
     echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
     include 'menu_bar.php';
     echo "<form id='form1' name='form1' action='scheduling.php' method='post'>\n";
     echo "<table id='tbl_scheduling'>\n";
     echo "<tr><th>schedule</th><th>categories</th><th>keywords</th><th>startDate</th><th>length</th><th></th><th></th><th></th></tr>\n";
     echo "<tr><td><textarea id='schedule' name='schedule'>".$schedule."</textarea></td>\n";
     echo "<td>".$categories."</td>\n";
     echo "<td>".$keywords."</td>\n";
     echo "<td><input type='text' id='tb_start_date' name='tb_start_date' value='".$startDate."' /></td>\n";
     echo "<td><input type='text' id='tb_length' name='tb_length' value='".$length."' /></td>\n";
     echo "<td><input type='text' id='tb_category' name='tb_category' value'".$tb_category."' /></td>\n";
     echo "<td><input type='text' id='tb_keyword' name='tb_keyword' value='".$tb_keyword."' /></td>\n";
     echo "<td><input type='button' id='btn_submit' name='btn_submit' /></td></tr>\n";
     echo "</table>\n";
     echo "<input type='hidden' id='hf_update' name='hf_update' value='' />\n";
     echo "<input type='hidden' id='hf_schedule' name='hf_schedule' value='' />\n";
     echo "<input type='hidden' id='hf_categories' name='hf_categories' value='' />\n";
     echo "<input type='hidden' id='hf_keywords' name='hf_keywords' value='' />\n";
     echo "<input type='hidden' id='hf_start_date' name='hf_start_date' value='' />\n";
     echo "<input type='hidden' id='hf_length' name='hf_length' value='' />\n";
     echo "</form>\n";
   } else {
     header("Location: cms.php");
   }
   //$filter defines the selected items to pre-select
   function createDropdown($delim, $options, $name, $filter, $multiple = false) {
      $options_array = explode(",",$options);
      $dd = "<select id='".$name."' name='".$name."'".($multiple ? " multiple='multiple'" : "").">\n";
      foreach ($options_array as $option) {
          $filters = explode(",",$filter);
          foreach ($filters as $filter) {
              $dd .= "<option value='".$option."'".(array_key_exists($filter, $options_array) ? " selected='selected'" : "").">".$option."</option>\n";
          }
      }
      $dd .= "</select>\n";
      return $dd;
   }
  ?>
</body>
</html>