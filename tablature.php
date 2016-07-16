<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $title = "";
  $artist = "";
  $tab_content = "";
  
  if (isset($_POST["hf_title"]) && $_POST["hf_title"]) {
    $title = $_POST["hf_title"];
  }
  if (isset($_POST["hf_artist"]) && $_POST["hf_artist"]) {
    $artist = $_POST["hf_artist"];
  }
  if (isset($_POST["hf_tab_content"]) && $_POST["hf_tab_content"]) {
    $tab_content = $_POST["hf_tab_content"];
  }
  if (isset($_POST["btn_submit"])) {
    if ($tab_content != "" && $artist != "" && $title != "") {
      DBLogin("a8823305_audio");
      DBQuery("INSERT INTO tablature (title,artist,tab_content,created) VALUES ('".$title."','".$artist."','".$tab_content."','".new DateTime("NOW")."')");
      DBC();
    }
  }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_submit").on("click", function() {
      $("#hf_title").val($("#tb_title").val());
      $("#hf_artist").val($("#tb_artist").val());
      $("#hf_tab_content").val($("#tb_tab_content").val());
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
    echo "<form id='form1' name='form1' action='tablature.php' method='post'>\n";
    echo "<table id='tbl_tablature'>\n";
    echo "<tr><th>title</th><th>artist</th><th>tablature</th><th>created</th></tr>\n";
    $tablature = getTablature();
    foreach ($tablature as $tab) {
      $id = $tab["id"];
      $title = $tab["title"];
      $artist = $tab["artist"];
      $tab_content = $tab["tablature"];
      $created = $tab["created"];
      echo "<tr><td>".$title."</td><td>".$artist."</td><td>".$tab_content."</td><td>".$created."</td></tr>\n";
    }
    echo "<tr><td><b>Title</b></td><td><b>Artist</b></td><td><b>Tablature</b></td><td>Submit A Tab of A Track Below</td></tr>\n";
    echo "<tr><td><input type='text' id='tb_title' name='tb_title' value='' /></td>\n";
    echo "<td><input type='text' id='tb_artist' name='tb_artist' value='' /></td>\n";
    echo "<td><input type='text' id='tb_tab_content' name='tb_tab_content' value='' /></td><td></td></tr>\n";
    echo "</table>\n";
    echo "<input type='hidden' id='hf_title' name='hf_title' value='' />\n";
    echo "<input type='hidden' id='hf_artist' name='hf_artist' value='' />\n";
    echo "<input type='hidden' id='hf_tab_content' name='hf_tab_content' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getTablature() {
    $tablature = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM tablature");
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $tablature[] = $row;
    }
    DBC();
    return $tablature;
  }
?>
</body>
</html>