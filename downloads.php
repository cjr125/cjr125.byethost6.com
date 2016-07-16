<!DOCTYPE html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $error = "";
  $selected_downloads = array();
 
  if (isset($_POST["hf_selected_downloads"]) && $_POST["hf_selected_downloads"] != "") {
    $selected_downloads = explode(",", $_POST["hf_selected_downloads"]);
    foreach ($selected_downloads as $selected_download) {
      $startIndex = strrpos($selected_download, "/");
      $filename = substr($selected_download, $startIndex, strrpos($selected_download, ".") - $startIndex);
      file_put_contents($filename, $selected_download);
    }
  }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_download").on("click", function() {
      var selected_downloads = "";
      for (var i = 0; i < $("#cb_select").length; i++) {
        if ($("#cb_select")[i].prop(":checked")) {
          selected_downloads += (selected_downloads ? this.parent().firstChild().innerHtml() : "," + this.parent().firstChild().innerHtml());
        }
      }
      $("#hf_selected_downloads").val(selected_downloads);
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
    echo "<form id='form1' name='form1' action='downloads.php' method='post'>\n";
    echo "<table id='tbl_downloads'>\n";
    echo "<tr><th>url</th><th>selected</th></tr>\n";
    $downloads = getDownloads();
    foreach ($downloads as $download) {
      $url = $download["url"];
      $selected = download["selected"];
      echo "<tr><td>".$url."</td><td><input type='checkbox' id='cb_selected' name='cb_selected' checked='".($selected ? "checked" : "unchecked")."' /></td></tr>\n";
    }
    echo "<tr><td></td><td><input type='button' id='btn_download' name='btn_download' value='Download' /></td></tr>\n";
    echo "</table>\n";
    echo "<input type='hidden' id='hf_selected_downloads' name='hf_selected_downloads' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getDownloads() {
    $downloads = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM downloads");
    if ($result->num_rows == 0) {
      echo "No rows found.";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $downloads[] = $row;
    }
    DBC();
    return $downloads;
  }
?>
</body>
</html>