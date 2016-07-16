<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $error = "";
  $page_url = "";
 
  if (isset($_POST["hf_page_url"]) && $_POST["hf_page_url"]) {
    $page_url = $_POST["hf_page_url"];
  }
  if ($page_url != "") {
    header("Location: ".$page_url);
  }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_proceed").on("click", function() {
      $("#hf_page_url").val($(".navigationItem a").attr("href"));
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
    echo "<form id='form1' name='form1' action='navigation.php' method='post'>\n";
    echo "<table id='tbl_navigation'>\n";
    echo "<tr><th>page name</th><th>description</th><th>preview</th></tr>\n";
    $navigation = getNavigation();
    foreach ($navigation as $navigation_row) {
      $page_name = $navigation["page_name"];
      $description = $navigation["description"];
      $preview = $navigation["preview"];
      echo "<tr><td>".$page_name."</td><td>".$description."</td><td>".$preview."</td></tr>\n";
    }
    echo "<tr><td colspan='2'></td><td><input type='button' id='btn_proceed' name='btn_proceed' value='Proceed' /></td></tr>\n";
    echo "</table>\n";
    echo "<input type='hidden' id='hf_page_url' name='hf_page_url' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getNavigation() {
    $navigation = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM navigation");
    if ($result->num_rows == 0) {
      echo "No rows found.";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $navigation[] = $row;
    }
    DBC();
    return $navigation;
  }
?>
</body>
</html>