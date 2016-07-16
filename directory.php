<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';

  $user = $_COOKIE["user"];
  $directory_listings = "";
  $limit = 10;

  if (isset($_POST["hf_limit"]) && $_POST["hf_limit"] != "") {
    $limit += $_POST["hf_limit"];
    $directory_listing = getDirectoryListings($limit);
  }
?>
<script type="text/javascript" src="js/jquery-1.10.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_see_more").on("click", function() {
      $("#hf_limit").val($("#hf_limit").val() + 10);
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
    echo "<form id='form1' name='form1' action='directory.php' method='post'>\n";
    echo "<h1>Here are some examples of some successful projects that have been created using the workstation</h1>\n";
    echo "<div id='directoryContainer'>\n";
    if ($directory_listings == "") {
      $directory_listings = getDirectoryListings($limit);
    }
    foreach ($directory_listings as $listing) {
      echo "<div class='listing'>\n";
      echo "<h4>".$listing["heading"]."</h4>\n";
      echo "<a href=\"".$listing["destinationUrl"]."\"><img src=\"".$listing["imageUrl"]."\" alt=\"".$listing["imageAltText"]."\" /></a>\n";
      echo $listing["description"]."\n";
      echo "</div>\n";
    }
    echo "</div>\n";
    echo "<input type='hidden' id='hf_limit' name='hf_limit' value='10' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getDirectoryListings($limit) {
    $direcory_listings = array();
    DBLogin("a8823305_audio");
    $SQL = "SELECT * FROM directory_listings LIMIT ".$limit;
    $result = DBQuery($SQL);
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $direcory_listings[] = $row;
    }
    DBC();
    return $direcory_listings;
  }
?>
</body>
</html>