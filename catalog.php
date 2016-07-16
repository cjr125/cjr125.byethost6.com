<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $error = "";
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_add_to_cart").on("click", function() {
      var rowIndex = $("#btn_add_to_cart").index(this);
      var itemId = $("#btn_add_to_cart")[rowIndex].parent().firstChild().innerHtml();
      $("#hf_cart_items").val($("#hf_cart_items").val() == "" ? itemId : "," + itemId);
      $("#form1").submit();
    }
  });
</script>
</head>
<body>
<?php
  if (logged_in()) {
    echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    echo "<form id='form1' name='form1' action='catalog.php' method='post'>\n";
    echo "<table id='tbl_catalog'>\n";
    echo "<tr><th>item id</th><th>item name</th><th>item description</th><th>price</th><th></th></tr>\n";
    $catalog = getCatalog();
    foreach ($catalog as $catalog_row) {
      $item_id = $catalog_row["item_id"];
      $item_name = $catalog_row["item_name"];
      $item_description = $catalog_row["item_description"];
      $price = $catalog_row["price"];
      echo "<tr><td>".$item_id."</td><td>".$item_name."</td><td>".$item_description."</td><td>".$price."</td><td><input type='button' id='btn_add_to_cart' name='btn_add_to_cart' value='Add To Cart' /></td></tr>\n";
    }
    echo "</table>\n";
    echo "<input type='hidden' id='hf_cart_items' name='hf_cart_items' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getCatalog() {
    $catalog = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM catalog");
    if ($result->num_rows == 0) {
      echo "No rows found.";
      exit;
    }
    while ($row = $result-fetch_array(MYSQL_ASSOC)) {
      $catalog[] = $row;
    }
    DBC();
    return $catalog;
  }
?>
</body>
</html>