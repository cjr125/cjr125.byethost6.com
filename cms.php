<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
      require 'HtmlUtil.php';

?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript">
  var starttime,eventId,mediaId,eventType,created;
  eventId = <?php echo $eventId; ?>;
  mediaId = <?php echo $mediaId; ?>;
  created = new Date();
  created = created.getTime();
  eventType = <?php echo $eventType; ?>;
  $(document).ready(function() {
    var d = new Date();
    starttime = d.getTime();
    $(window).unload(function() {
      d = new Date();
      endtime = d.getTime();
      $.ajax({ 
        url: "time.php",
        data: {'time': endtime - starttime,
               'eventId': eventId,
               'mediaId': mediaId,
               'eventType': eventType,
               'created': created}
      });
    });
    var cache_breaker = randomString(32, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
    $("#form1").attr('action', $("#form1").attr('action') + '?r=' + cache_breaker);
    $("#start_date").datepicker();
    $("#end_date").datepicker();
    $("#start_date").change(function() {
      if ($("#start_date").val().indexOf(":") == -1) {
        var date = new Date();
        var minutes = formatTime(date.getMinutes());
        var seconds = formatTime(date.getSeconds());
        $("#start_date").val($("#start_date").val() + " " + date.getHours() + ":" + minutes + ":" + seconds);
      }
    });
    $("#end_date").change(function() {
      if ($("#end_date").val().indexOf(":") == -1) {
        var date = new Date();
        var minutes = formatTime(date.getMinutes());
        var seconds = formatTiem(date.getSeconds());
        $("#end_date").val($("#end_date").val() + " " + date.getHours() + ":" + minutes + ":" + seconds);
      }
    });
    $("#dd_keywords").change(function() {
      var selected_keywords = document.cookie["selected_keywords"];
      if (selected_keywords) {
        selected_keywords += (selected_keywords != "" ? "," : "") + $("#dd_keywords:selected").val();
        $("#content").val($("#content").val().replace("/[keyword]/g", selected_keywords));
      }
      document.cookie["selected_keywords"] = selected_keywords;
    });
    $("#cb_filter").prop("checked",<?php echo ($filter_by_keyword ? "true" : "false"); ?>);
    $("#dd_ua_string_filters").change(function() {
      var selected_ua_string_filters = document.cookie["selected_ua_string_filters"];
      if (selected_ua_string_filters) {
        selected_ua_string_filters += (selected_ua_string_filters != "" ? "," : "") + $("#dd_ua_string_filters:selected").val();
      }
      document.cookie["selected_ua_string_filters"] = selected_ua_string_filters;
    });
    $("#dd_state_filters").change(function() {
      var selected_state_filters = document.cookie["selected_state_filters"];
      if (selected_state_filters) {
        selected_state_filters += (selected_state_filters != "" ? "," : "") + $("#dd_selected_state_filters:selected").val();
      }
      document.cookie["selected_state_filters"] = selected_state_filters;
      $("#hf_selected_state_filters").val(selected_state_filters);
      $("#form1").submit();
    });
    $("#dd_event_types").change(function() {
      var selected_event_types = document.cookie["selected_event_types"];
      if (selected_event_types) {
        selected_event_types += (selected_event_types != "" ? "," : "") + $("#dd_event_types:selected").val();
      }
      document.cookie["selected_event_types"] = selected_event_types;
    });
    $("#dd_content_spots").change(function() {
      var selected_content_spot_names = document.cookie["selected_content_spot_names"];
      if (selected_content_spot_names) {
        selected_content_spot_names += (selected_content_spot_names != "" ? "," : "") + $("#dd_content_spots:selected").val();
      }
      document.cookie["selected_content_spot_names"] = selected_content_spot_names;
      var selected_content_spot_id = -1;
      selected_content_spot_id = $("#dd_content_spots:selected").val();
      if (selected_content_spot_id > -1) {
        $("#hf_selected_content_spot_id") = selected_content_spot_id;
        $("#form1").submit();
      }
    });
    $("#dd_content_spot_urls").change(function() {
      var selected_content_spot_urls = document.cookie["selected_content_spot_urls"];
      if (selected_content_spot_urls) {
        selected_content_spot_urls += (selected_content_spot_urls != "" ? "," : "") + $("#dd_content_spot_urls:selected").val();
      }
      document.cookie["selected_content_spot_urls"] = selected_content_spot_urls;
    });
    $("#dd_content_spot_categories").change(function() {
      var selected_content_spot_categories = document.cookie["selected_content_spot_categories"];
      if (selected_content_spot_categories) {
        selected_content_spot_categories += (selected_content_spot_categories != "" ? "," : "") + $("#dd_content_spot_categories:selected").val();
      }
      document.cookie["selected_content_spot_categories"] = selected_content_spot_categories;
    });
    $("#tb_content_spot_name").on("click", function() {
      if ($("#tb_content_spot_name").val() == "Add Content Spot Name") {
        $("#tb_content_spot_name").val("");
      }
    });
    $("#btn_add_content_spot_name").on("click", function() {
      $("#hf_content_spot_name").val($("#tb_content_spot_name").val());
    });
    $("#tb_content_spot_url").on("click", function() {
      if ($("#tb_content_spot_url").val() == "Add Content Spot Url") {
        $("#tb_content_spot_url").val("");
      }
    });
    $("#btn_add_content_spot_url").on("click", function() {
      $("#hf_content_spot_url").val($("#tb_content_spot_url").val());
    });
    $("#tb_content_spot_category").on("click", function() {
      if ($("#tb_content_spot_category").val() == "Add Content Spot Category") {
        $("#tb_content_spot_category").val("");
      }
    });
    $("#btn_add_content_spot_category").on("click", function() {
      $("#hf_content_spot_category").val("#tb_content_spot_category").val());
    });
    $("#width").on("click", function() {
      if ($("#width").val() == "0") {
        $("#width").val("");
      }
    });
    $("#height").on("click", function() {
      if ($("#height").val() == "0") {
        $("#height").val("");
      }
    });
    $("#tb_description").on("click", function() {
      if ($("#tb_description").val() == "Enter a description") {
        $("#tb_description").val("");
      }
    });
    $("#tb_location").on("click", function() {
      if ($("tb_location").val() == "Add location name") {
        $("#tb_location").val("");
      }
    });
    $("#add_keyword_category").on("click", function() {
      if ($("#add_keyword_category").val() == "Category") {
        $("#add_keyword_category").val("");
      }
    });
    $("#add_city_filter").on("click", function() {
      if ($("#add_city_filter").val() == "Add a City Filter") {
        $("#add_city_filter").val("");
      }
    });
    $("#btn_add_city_filter").on("click", function() {
      clearHiddenFields();
      $("#hf_city_filter").val($("#add_city_filter").val().trim());
      $("#form1").submit();
    });
    $("#add_ua_string_filter").on("click", function() {
      if ($("#add_ua_string_filter").val() == "Add UA String Filter") {
        $("#add_ua_string_filter").val("");
      }
    });
    $("#btn_add_ua_string_filter").on("click", function() {
      clearHiddenFields();
      $("#hf_ua_string_filter").val($("#add_ua_string_filter").val().trim());
      $("#form1").submit();
    });
    $("#add_event_type").on("click", function() {
      if ($("#add_event_type").val() == "Add Event Type") {
        $("#add_event_type").val("");
      }
    });
    $("#btn_add_event_type").on("click", function() {
      clearHiddenFields();
      $("#hf_event_type").val($("#add_event_type").val().trim());
      $("#form1").submit();
    });
    $("#add_tag_type").on("click", function() {
      if ($("#add_tag_type").val() == "Add Tag Type") {
        $("#add_tag_type").val("");
      }
    });
    $("#btn_add_tag_type").on("click", function() {
      clearHiddenFields();
      $("#hf_add_tag_type").val($("#add_tag_type").val().trim());
      $("#form1").submit();
    });
    $("#add_keyword").on("click", function() {
      if ($("#add_keyword").val() == "Add Keyword") {
        $("#add_keyword").val("");
      }
    });
    $("#btn_add_keyword").on("click", function() {
      clearHiddenFields();
      $("#hf_update_keywords").val("update_keywords");
      $("#hf_add_keyword").val($("#add_keyword").val().trim());
      $("#form1").submit();
    });
    $("#btn_generate_keywords").on("click", function() {
      clearHiddenFields();
      $("#hf_keywords").val("keywords");
      $("#form1").submit();
    });
    $("#btn_update_keywords").on("click", function() {
      clearHiddenFields();
      $("#hf_update_keywords").val("update_keywords");
      $("#form1").submit();
    });
    $("#btn_save").on("click", function() {
      clearHiddenFields();
      $("#hf_rand").val(cache_breaker);
      $("#hf_start_date").val($("#start_date").val());
      $("#hf_end_date").val($("#end_date").val());
      $("#hf_cost_per_click").val($("#cost_per_click").val());
      $("#hf_description").val($("#tb_description").val());
      $("hf_location").val($("#tb_location").val());
      $("#hf_keyword_category").val($("#add_keyword_category").val());
      $("#hf_save").val("save");
      $("#form1").submit();
    });
    $("#btn_update").on("click", function() {
      clearHiddenFields();
      $("#hf_rand").val(cache_breaker);
      $("#hf_start_date").val($("#start_date").val());
      $("#hf_end_date").val($("#end_date").val());
      $("#hf_cost_per_click").val($("#cost_per_click").val());
      $("#hf_location").val($("#location").val());
      $("#hf_keyword_category").val($("#add_keyword_category").val());
      $("#hf_keyword_relevance").val("<?php echo $keywordRelevance[$keywordRelevance->length-1]; ?>");
      $("#hf_update").val("update");
      $("#form1").submit();
    });
    $("#btn_delete").on("click", function() {
      clearHiddenFields();
      $("#hf_delete").val("delete");
      $("#form1").submit();
    });
    $("#btn_filter").on("click", function() {
      clearHiddenFields();
      $("#hf_filter").val("filter");
      $("#hf_ua_filter").val($("#add_ua_string_filter").val().trim());
      $("#hf_add_tag_type").val($("#add_tag_type").val().trim());
      $("#hf_add_keyword").val($("#add_keyword").val().trim());
      $("#form1").submit();
    });
    $("#btn_clear").on("click", function() {
      clearHiddenFields();
      $("#hf_clear").val("clear");
      $("#form1").submit();
    });
    function clearHiddenFields() {
      $("#hf_rand").val("");
      $("#hf_start_date").val("");
      $("#hf_end_date").val("");
      $("#hf_cost_per_click").val("");
      $("#hf_selected_content_spot_id").val("");
      $("#hf_content_spot_name").val("");
      $("#hf_content_spot_url").val("");
      $("#hf_content_spot_category").val("");
      $("#hf_description").val("");
      $("#hf_location").val("");
      $("#hf_keyword_category").val("");
      $("#hf_city_filter").val("");
      $("#hf_ua_string_filter").val("");
      $("#hf_state_filters").val("");
      $("#hf_event_type").val("");
      $("#hf_add_tag_type").val("");
      $("#hf_add_keyword").val("");
      $("#hf_keywords").val("");
      $("#hf_save").val("");
      $("#hf_update").val("");
      $("#hf_delete").val("");
      $("#hf_clear").val("");
    }
    function randomString(length, chars) {
      var result = '';
      for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
      return result;
    }
    function formatTime(time) {
      return (time.length < 2 ? "0" + time : time);
    }
  });
</script>

<link rel="stylesheet" href="css/ui-darkness/jquery-ui-1.10.4.custom.min.css" />
<link rel="stylesheet" type="text/css" href="css/cms.css" />   
</head>
<body class="cms"> 
<?php
      if (logged_in()) {
          echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
          include 'menu_bar.php';
          echo "<form id='form1' name='form1' action='cms.php' method='post'>\n";
          echo "<table id='add_media' border='1'><tr><th>content</th><th>contentType</th><th>keywords</th><th>url</th><th></th></tr>\n";
          echo "<tr><td><textarea id='content' name='content' rows='25' cols='60'>".str_replace("\'","'",$content)."</textarea></td>\n";
          echo "<td style='vertical-align:top;'><input type='text' id='contentType' name='contentType' value='".$contentType."' style='width:80px;' /><br>\n";
          echo "<label for='width'>Width:</label><br>\n"; 
          echo "<input type='text' id='width' name='width' value='".$width."' style='color:grey;width:80px' /><br>\n";
          echo "<label for='height'>Height:</label><br>\n";
          echo "<input type='text' id='height' name='height' value='".$height."' style='color:grey;width:80px' /></td>\n";
          echo "<td id='td_keywords' style='vertical-align:top;'><input type='text' id='add_keyword_category' name='add_keyword_category' value='".$keywordCategory."' class='editable' /><br>\n";
          if ($keywords != "") {
              echo "<label for='cb_filter'>Filter by Keywords:</label><input type='checkbox' id='cb_filter' name='cb_filter' style='vertical-align:bottom' /><br>\n";
              echo $keywords."<br>\n";
          }
          if ($city_filters != "") {
              echo "<label for='dd_city_filters'>City Filters:</label><br>\n";
              echo $city_filters."<br>\n";
          }
          if ($stateFilters != "") {
              echo "<label for='dd_state_filters'>State Filters:</label><br>\n";
              echo $stateFilters."<br>\n";
          }
          if ($ua_string_filters != "") {
              echo "<label for='dd_ua_string_filters'>UA String Filters:</label><br>\n";
              echo $ua_string_filters."<br>\n";
          }
          if ($eventTypes != "") {
              echo "<label for='dd_event_types'>Event Types:</label><br>\n";
              echo $eventTypes."<br>\n";
          }
          if ($content_spots != "") {
              echo "<label for='dd_content_spots'>Content Spots:</label><br>\n";
              echo $content_spots."<br>\n";
          }
          if ($content_spot_urls != "") {
              echo "<label for='dd_content_spot_urls'>Content Spot Urls:</label><br>\n";
              echo $content_spot_urls."<br>\n";
          }
          if ($content_spot_categories != "") {
              echo "<label for='dd_content_spot_categories'>Content Spot Categories:</label><br>\n";
              echo $content_spot_categories."<br>\n";
          }
          echo "</td>\n";
          echo "<td style='vertical-align:top;'><input type='text' id='url' name='url' value='".$url."' style='width:350px;' /><br>\n";
          echo "<label for='start_date' class='lbl_date'>Start Date:</label>&nbsp;<label for='end_date' class='lbl_date'>End Date:</label>&nbsp;<label for='cost_per_click'>Cost/Click:</label><br>\n";
          echo "<input type='text' id='start_date' name='start_date' value='".$startDate->format('m/d/Y H:i:s')."' class='tb_date' />";
          echo "<input type='text' id='end_date' name='end_date' value='".$endDate->format('m/d/Y H:i:s')."' class='tb_date' />";
          echo "<input type='text' id='cost_per_click' name='cost_per_click' value='".$cost_per_click."' style='width:65px;' /><br>\n";
          if ($error != "") {
              echo "<span class='error'>".$error."</span><br>\n";
          }
          if ($tag_types != "") {
              echo "Tag Types: <br>\n";
              $tag_types = explode(",",$tag_types);
              foreach ($tag_types as $tag_type) {
                  echo "&lt;".$tag_type."&gt;";
              }
          }
          echo "</td>\n";
          echo "<td style='vertical-align:top;'><input type='text' id='tb_content_spot_name' value='".$tb_content_spot_name."' class='editable' /><br>\n";
          echo "<input type='button' id='btn_add_content_spot_name' value='Add Content Spot Name'></input><br>\n";
          echo "<input type='text' id='tb_content_spot_url' value='".$tb_content_spot_url."' class='editable' /><br>\n";
          echo "<input type='button' id='btn_add_content_spot_url' value='Add Content Spot Url'></input><br>\n";
          echo "<input type='text' id='tb_content_spot_category' value='".$tb_content_spot_category."' class='editable' /><br>\n";
          echo "<input type='button' id='btn_add_content_spot_category' value='Add Content Spot Category'></input><br>\n";
          echo "<input type='text' id='tb_description' name='tb_description' value='".$tb_description."' class='editable' /><br>\n";
          echo "<input type='text' id='tb_location' name='tb_location' value='".$tb_location."' class='editable' /><br>\n";
          echo "<input type='text' id='add_city_filter' name='add_city_filter' value='"
               .($tb_city_filter == "" ? "Add a City Filter" : $tb_city_filter)."' class='editable' /><br>\n";
          echo "<input type='button' id='btn_add_city_filter' name='btn_add_city_filter' value='Add a City Filter'></input><br>\n";
          echo "<input type='text' id='add_ua_string_filter' name='add_ua_string_filter' value='"
               .($tb_ua_string_filter == "" ? "Add UA String Filter" : $tb_ua_string_filter)."' class='editable' /><br>\n";
          echo "<input type='button' id='btn_add_ua_string_filter' name='btn_add_ua_string_filter' value='Add UA String Filter'></input><br>\n";
          echo "<input type='text' id='add_event_type' name='add_event_type' value='".($tb_event_type == "" ? "Add Event Type" : $tb_event_type)."' class='editable' /><br>\n";
          echo "<input type='button' id='btn_add_event_type' name='btn_add_event_type' value='Add Event Type'></input><br>\n";
          echo "<input type='text' id='add_tag_type' name='add_tag_type' value='".($tb_tag_type == "" ? "Add Tag Type" : $tb_tag_type)."' class='editable' /><br>\n";
          echo "<input type='button' id='btn_add_tag_type' name='btn_add_tag_type' value='Add Tag Type'></input><br>\n";
          echo "<input type='text' id='add_keyword' name='add_keyword' value='".($tb_keyword == "" ? "Add Keyword" : $tb_keyword)."' class='editable' /><br>\n";
          echo "<input type='button' id='btn_add_keyword' name='btn_add_keyword' value='Add Keyword'></input><br>\n";
          echo "<input type='button' id='btn_generate_keywords' name='keywords' value='Generate Keywords'></input><br>\n";
          echo "<input type='button' id='btn_save' name='save' value='Save Media'></input><br>\n";
          if ($id > -1) {
              echo "<input type='button' id='btn_update' name='update' value='Update Media'></input><br>\n";
              echo "<input type='button' id='btn_delete' name='delete' value='Delete Media'></input><br>\n";
          }
          echo "<input type='button' id='btn_filter' name='filter' value='Filter'></input>\n";
          echo "<input type='button' id='btn_clear' name='clear' value='Clear'></input>\n";
          echo "</td></tr></table>\n";
          echo "<input type='hidden' id='hf_rand' name='hf_rand' value='' />\n";
          echo "<input type='hidden' id='hf_start_date' name='hf_start_date' value='' />\n";
          echo "<input type='hidden' id='hf_description' name='hf_description' value='' />\n";
          echo "<input type='hidden' id='hf_location' name='hf_location' value='' />\n";
          echo "<input type='hidden' id='hf_end_date' name='hf_end_date' value='' />\n";
          echo "<input type='hidden' id='hf_cost_per_click' name='hf_cost_per_click' value='' />\n";
          echo "<input type='hidden' id='hf_selected_content_spot_id' name='hf_selected_content_spot_id' value='' />\n";
          echo "<input type='hidden' id='hf_content_spot_name' name='hf_content_spot_name' value='' />\n";
          echo "<input type='hidden' id='hf_content_spot_url' name='hf_content_spot_url' value='' />\n";
          echo "<input type='hidden' id='hf_content_spot_category' name='hf_content_spot_category' value='' />\n";
          echo "<input type='hidden' id='hf_keyword_category' name='hf_keyword_category' value='' />\n";
          echo "<input type='hidden' id='hf_keyword_relevance' name='hf_keyword_relevance' value='' />\n";
          echo "<input type='hidden' id='hf_city_filter' name='hf_city_filter' value='' />\n";
          echo "<input type='hidden' id='hf_ua_string_filter' name='hf_ua_string_filter' value='' />\n";
          echo "<input type='hidden' id='hf_state_filters' name='hf_state_filters' value='' />\n";
          echo "<input type='hidden' id='hf_event_type' name='hf_event_type' value='' />\n";
          echo "<input type='hidden' id='hf_add_tag_type' name='hf_add_tag_type' value='' />\n";
          echo "<input type='hidden' id='hf_add_keyword' name='hf_add_keyword' value='' />\n";
          echo "<input type='hidden' id='hf_keywords' name='hf_keywords' value='' />\n";
          echo "<input type='hidden' id='hf_update_keywords' name='hf_update_keywords' value='' />\n";
          echo "<input type='hidden' id='hf_save' name='hf_save' value='' />\n";
          echo "<input type='hidden' id='hf_update' name='hf_update' value='' />\n";
          echo "<input type='hidden' id='hf_delete' name='hf_delete' value='' />\n";
          echo "<input type='hidden' id='hf_filter' name='hf_filter' value='' />\n";
          echo "<input type='hidden' id='hf_clear' name='hf_clear' value='' />\n";
          echo "</form>\n";
          if ($id > -1) {
              echo "<iframe src='media.php?id=".$id."&rand=".$rand."' style='width:".($width == 0 ? 1194 : $width)."px;".($height == 0 ? "" : "height:"
                   .$height."px;")."'></iframe>\n";
          }
      }
      else {
          echo "<form id='cmslogin' action='cms.php' method='post'>\n";
          echo "<span>Username:</span><input id='user' type='text' name='user'><br>\n";
          echo "<span>Password:</span><input id='passwd' type='password' name='passwd'><br>\n";
          echo "<input id='submit' type='submit' name='submit'>\n";
          echo "</form>\n";
      }
?>
</body>
</html>