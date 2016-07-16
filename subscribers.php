<!DOCTYPE html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $error = "";
  $client_id = $_GET["client_id"];
  $name = "";
  $address = "";
  $city = "";
  $state = "";

  if (isset($_POST["hf_name"]) && $_POST["hf_name"] != "") {
    $name = $_POST["hf_name"];
  }
  if (isset($_POST["hf_address"]) && $_POST["hf_address"] != "") {
    $address = $_POST["hf_address"];
  }
  if (isset($_POST["hf_city"]) && $_POST["hf_city"] != "") {
    $city = $_POST["hf_city"];
  }
  if (isset($_POST["hf_state"]) && $_POST["hf_state"] != "") {
    $state = $_POST["hf_state"];
  }
  if (isset($_POST["btn_submit"])) {
    if ($state != "" && $city != "" && $address != "" && $name != "") {
      DBLogin("a8823305_audio");
      DBQuery("INSERT INTO subscribers (name,address,city,state) VALUES ('".$name."','".$address."','".$city."','".$state."')");
      DBC();
    }
  }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_submit").on("click", function() {
      $("#hf_name").val($("#tb_name").val());
      $("#hf_address").val($("#tb_address").val());
      $("#hf_city").val($("#tb_city").val());
      $("#hf_state").val($("#tb_state").val());
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
    echo "<form id='form1' name='form1' action='subscribers.php' method='post'>\n";
    echo "<table id='tbl_subscribers'>\n";
    echo "<tr><th>name</th><th>address</th><th>city</th><th>state</th></tr>\n";
    $subscribers = getSubscribers($client_id);
    foreach ($subscribers as $subscriber) {
      $name = $subscriber["name"];
      $address = $subscriber["address"];
      $city = $subscriber["city"];
      $state = $subscriber["state"];
      echo "<tr><td>".$name."</td><td>".$address."</td><td>".$city."</td><td>".$state."</td></tr>\n";
    }
    echo "</table>\n";
    echo "<input type='hidden' id='hf_name' name='hf_name' value='' />\n";
    echo "<input type='hidden' id='hf_address' name='hf_address' value='' />\n";
    echo "<input type='hidden' id='hf_city' name='hf_city' value='' />\n";
    echo "<input type='hidden' id='hf_state' name='hf_state' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getSubscribers($client_id) {
    $subscribers = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM subscribers WHERE client_id = ".$client_id);
    if ($result->num_rows == 0) {
      echo "No rows found.";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $subscribers[] = $row;
    }
    DBC();
    return $subscribers;
  }
?>
</body>
</html>