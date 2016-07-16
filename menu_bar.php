<?php
  include 'db.php';
  $user = $_COOKIE["user"];
  $group = (isset($_GET["group"]) && $_GET["group"] != "" ? $_GET["group"] : "default");
  $messageCount = 0;
  DBLogin("b6_17569910_audio");
  $result = DBQuery("SELECT COUNT(*) AS messageCount FROM private_messages WHERE recipient = '".$user."'");
  if ($result->num_rows > 0) {
    $messageCount = $result->fetch_array(MYSQL_ASSOC)["messageCount"];
  }
  DBC();
  echo "<ul><li><a href=\"listmedia.php\">List Media</a></li><li><a href=\"contentSpots.php\">Content Spots</a></li><li><a href=\"events.php\">Events</a></li><li><a href=\"community.php".($group == "default" ? "" : "?group=".$group)."\">Community".($messageCount > 0 ? " (".$messageCount.")" : "")."</a></li><li><a href=\"keywordCategories.php\">Keyword Categories</a></li><li><a href=\"schedules.php\">Schedules</a></li><li><a href=\"account.php\">Account</a></li></ul><br>\n";
?>