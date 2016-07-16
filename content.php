<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $content_id = $_GET["content_id"];
  $landing_page_url = $_GET["landing_page_url"];
  $error = "";

  if (isset($_POST["hf_landing_page_url"]) && $_POST["hf_landing_page_url"] != "") {
    header("Location: ".$_POST["hf_landing_page_url"]);
  } else if (isset($landing_page_url) && $landing_page_url != "") {
    header("Location: ".$landing_page_url);
  }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $.extend({
    getUrlVars: function(){
      var vars = [], hash;
      var hashes = this.href.slice(this.href.indexOf('?') + 1).split('&');
      for(var i = 0; i < hashes.length; i++)
      {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
      }
      return vars;
    },
    getUrlVar: function(name){
      return $.getUrlVars()[name];
    });
    $("body").on("click", function() {
      $("#hf_landing_page_url").val(getUrlVars()["landing_page_url"]);
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
    if ($error != "") {
      echo "<span class='error'>".$error."</span><br>\n";
    }
    echo "<form id='form1' name='form1' action='content.php' method='post'>\n";
    echo "<table id='tbl_content'>\n";
    $content = getContent($content_id);
    echo "<tr><td>".$content."</td></tr>\n";
    echo "</table>\n";
    echo "<input type='hidden' id='hf_landing_page_url' name='hf_landing_page_url' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getContent($content_id) {
    $content = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM content WHERE id = ".$content_id);
    if ($result->num_rows == 0) {
      echo "No rows found.";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $content[] = $row;
    }
    DBC();
    return $content;
  }
?>
</body>
</html>