<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $error = "";
  $subject = "";
  $review = "";

  if (isset($_POST["hf_subject"]) && $_POST["hf_subject"]) {
    $subject = $_POST["hf_subject"];
  }
  if (isset($_POST["hf_review"]) && $_POST["hf_review"]) {
    $review = $_POST["hf_review"];
  }
  if (isset($_POST["btn_submit"])) {
    if ($review != "" && $subject != "") {
      DBLogin("a8823305_audio");
      DBQuery("INSERT INTO reviews (subject,review,username,created) VALUES ('".$subject."','".$review."','".$user."',NOW())");
      DBC();
    }
  }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btn_submit").on("click", function() {
      $("#hf_subject").val($("#tb_subject").val());
      $("#hf_review").val($("#tb_review").val());
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
    echo "<form id='form1' name='form1' action='reviews.php' method='post'>\n";
    echo "<tr><th>username</th><th>subject</th><th>review</th></tr>\n";
    $reviews = getReviews();
    foreach ($reviews as $review) {
      $subject = (is_null($review["subject"]) ? "" : $review["subject"]);
      $review_content = (is_null($review["review"]) ? "" : $reviw["review"]);
      $username = (is_null($review["username"]) ? "" : $review["username"]);
      echo "<tr><td>".$username."</td><td>".$subject."</td><td>".$review_content."</td></tr>\n";
    }
    echo "<tr><td><label for='tb_subject'>subject</label><input type='text' id='tb_subject' name='tb_subject' value='' /></td><td colspan='2'></td></tr>\n";
    echo "<tr><td><label for='tb_review'>review</label><textarea id='tb_review' name='tb_review'></textarea></td><td colspan='2'></td></tr>\n";
    echo "<tr><td colspan='2'></td><td><input type='button' id='btn_submit' name='btn_submit' value='Submit' /></td></tr>\n";
    echo "</table>\n";
    echo "<input type='hidden' id='hf_subject' name='hf_subject' value='' />\n";
    echo "<input type='hidden' id='hf_review' name='hf_review' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getReviews() {
    $reviews = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM reviews");
    if ($result->num_rows == 0) {
      echo "No rows found";
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $reviews[] = $row;
    }
    return $reviews;
  }
?>
</body>
</html>