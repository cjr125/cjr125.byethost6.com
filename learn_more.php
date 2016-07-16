<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
   window.onload = function() {
     window.open(location.href);
   }
</script>
</head>
<body>
<?php require 'db.php';
  $client_id = $_GET["client_id"];
  $error = "";
  $links = getLinks($client_id);
  foreach ($links as $link) {
    echo "<a href=\"".$link."\">learn more</a>";
  }
  function getLinks($client_id) {
    $links = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM links WHERE client_id = ".$client_id);
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $links[] = $row;
    }
    DBC();
    return $links;
  }
?>
</body>
</html>	