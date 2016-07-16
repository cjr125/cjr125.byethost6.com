<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-9">
<?php require 'db.php';
  $user = $_COOKIE["user"];
  $first = "";
  $last = "";
  $iconUrl = "";
  $age = 0;
  $accountStatus = "";
  $groupName = "";
  $groupIconUrl = "";
  $groupMemberCount = -1;
  $groupDescription = "";
  $groupDateCreated = new DateTime("0000-00-00 00:00:00");
  $registrationDate = new DateTime("0000-00-00 00:00:00");
  $lastLogin = new DateTime("0000-00-00 00:00:00");
  $maxArticles = 5;
  DBLogin("a8823305_audio");
  $result = DBQuery("SELECT a.first,a.last,a.iconUrl,a.favoritesListId,a.friendListId,a.groupId,a.gender,a.accountStatus,b.name,b.iconUrl,b.memberCount,
                            b.description,b.dateCreated,a.dateOfBirth,a.registrationDate,a.lastLogin FROM users a JOIN groups b ON a.groupId = b.id 
                     WHERE a.username = '".$user."'");
  if ($result->num_rows > 0) {
    $row = $result->fetch_array(MYSQL_ASSOC);
    $first = (is_null($row["first"]) ? $first : $row["first"]);
    $last = (is_null($row["last"]) ? $last : $row["last"]);
    $iconUrl = (is_null($row["iconUrl"]) ? $iconUrl : $row["iconUrl"]);
    $favoritesListId = (is_null($row["favoritesListId"]) ? -1 : intval($row["favoritesListId"]));
    $friendListId = (is_null($row["friendListId"]) ? -1 : intval($row["friendListId"]));
    $groupId = (is_null($row["groupId"]) ? -1 : intval($row["groupId"]));
    $gender = (is_null($row["gender"]) ? "" : $row["gender"]);
    $accountStatus = (is_null($row["accountStatus"]) ? $accountStatus : $row["accountStatus"]);
    $groupName = (is_null($row["name"]) ? $groupName : $row["name"]);
    $groupIconUrl = (is_null($row["iconUrl"] ? $groupIconUrl : $row["iconUrl"]);
    $groupMemberCount = (is_null($row["memberCount"]) ? $groupMemberCount : $row["memberCount"]);
    $groupDescription = (is_null($row["description"]) ? $groupDescription : $row["description"]);
    $groupDateCreated = (is_null($row["dateCreated"]) ? $groupDateCreated : $row["dateCreated"]);
    $age = (is_null($row["dateOfBirth"]) ? $age : intval((new DateTime("NOW"))->diff(new DateTime($row["dateOfBirth"]))->format("%Y")));
    $registrationDate = (is_null($row["registrationDate"]) ? $registrationDate : $row["dateCreated"]);
    $lastLogin = (is_null($row["lastLogin"]) ? $lastLogin : $row["lastLogin"]);
    if (favoritesListId > -1) {
      $result = DBQuery("SELECT a.title AS songTitle,a.creator AS songCreator,a.duration AS songDuration,a.location AS songLocation,
                                b.title AS albumTitle,b.artist AS albumArtist,b.location AS albumLocation,b.coverArtUrl AS albumCoverArtUrl,
                                c.name AS artistName,c.members AS artistMembers,c.label AS artistLabel,c.genre AS artistGenre,c.description AS artistDescription 
                         FROM cjrmusic a JOIN favoriteslists d ON a.id = d.songId JOIN albums b ON b.id = d.albumId JOIN artists c ON c.id = d.artistId WHERE d.id = ".$favoritesListId);
      //favorites
      while ($row = $result->fetch_array(MYSQL_ASSOC)) {
        $songTitle = (is_null($row["songTitle"]) ? "" : $row["songTitle"]);
        $songCreator = (is_null($row["songCreator"]) ? "" : $row["songCreator"]);
        $songDuration = (is_null($row["songDuration"]) ? "" : $row["songDuration"]);
        $songLocation = (is_null($row["songLocation"]) ? "" : $row["songLocation"]);
        $albumTitle = (is_null($row["albumTitle"]) ? "" : $row["albumTitle"]);
        $albumArtist = (is_null($row["albumArtist"]) ? "" : $row["albumArtist"]);
        $albumLocation = (is_null($row["albumLocation"]) ? "" : $row["albumLocation"]);
        $albumCoverArtUrl = (is_null($row["albumCoverArtUrl"]) ? "" : $row["albumCoverArtUrl"]);
        $artistName = (is_null($row["artistName"]) ? "" : $row["artistName"]);
        $artistMembers = (is_null($row["artistMembers"]) ? "" : $row["artistMembers"]);
        $artistLabel = (is_null($row["artistLabel"]) ? "" : $row["artistLabel"]);
        $artistGenre = (is_null($row["artistGenre"]) ? "" : $row["artistGenre"]);
        $artistDescription = (is_null($row["artistDescription"]) ? "" : $row["artistDescription"]);
      }
    }
    if ($friendListId > -1) {
      $result = DBQuery("SELECT a.username,b.dateAdded FROM users a JOIN friendlists b ON a.id = b.userId WHERE b.id = ".$friendListId);
      while ($row = $result->fetch_array(MYSQL_ASSOC)) {
        $friendUsername = (is_null($row["username"]) ? "" : $row["username"]);
        $friendDateAdded = (is_null($row["dateAdded"]) ? "" : $row["dateAdded"]);
      }
    }
  }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<link rel="stylesheet" href="css/smoothness/jquery-ui.css">
<script src="js/jquery-ui.js"></script>
<script src="js/upload.js" type="text/javascript"> </script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#send_msg_dialog").dialog({
      autoOpen:false,
      width:350,
      height:300,
      modal:true,
      buttons: {
        "Send Message": function() {
          $("#hf_subject").val($("#tb_subject").val());
          $("#hf_msg").val($("#tb_msg").val());
          $("#form1").submit();
        }
        Cancel: function() {
          $(this).dialog("close");
        }
      }
    });
    $("#btn_send_msg").on("click", function() {
      $("#send_msg_dialog").dialog("open");
    });
    function clearHiddenFields() {
      $("#hf_subject").val("");
      $("#hf_msg").val("");
    }
  });
</head>
<body>
<?php
  if (logged_in()) {
    echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    echo "<form method="POST" enctype="multipart/form-data" id="file_upload_form" name="aktar" action="ajax/picture_add.php">\n";
    echo "<table width="419" height="29" border="0">\n";
    echo "<tr>\n";
    echo "<th colspan="2" scope="col" >\n";
    echo "picture add\n";
    echo "</th></tr>\n";
    $max_no_img = 1;
    for ($res = 1; $res <= $max_no_img; $res++) { 
      echo "<tr><td width="92">picture</td>\n";
      echo "<td width="447"><input type="file" id="resim_".$res." size="%50" name="file[]" /></td>\n";
    }
    echo "</tr>\n";
    echo "</table>\n";
    echo "<input class="submit" type="submit" name="submit" value="Kaydet" class="kaydet">\n";
    echo "<iframe id="upload_target" name="upload_target" src="" style="display:none;">\n";
    echo "</iframe>\n";
    echo "</form>\n";
    echo "<form id='form1' name='form1' action='profile.php' method='post>\n";
    echo "<div id='send_msg_dialog'>\n";
    echo "<form>\n";
    echo "<fieldset>\n";
    echo "<label for='tb_subject'>Subject:</label>\n";
    echo "<input type='text' id='tb_subject' name='tb_subject' value='' class="text ui-widget-content ui-corner-all" />\n";
    echo "<textarea id='tb_msg' name='tb_msg' rows='30' cols='25' class="text ui-widget-content ui-corner-all"></textarea>\n";
    echo "<input type='button' id='btn_send_msg' name='btn_send_msg' value='Send Message' />\n";
    echo "</fieldset>\n";
    echo "</form>\n";
    echo "</div>\n";
    $articles = getArticlesByCreator($user);
    if (sizeof($articles) > 0) {
      echo "<div class='articlePreviewContainer'>\n";
      $i = 0;
      foreach ($articles as $article) {
        if ($i < $maxArticles) {
          $title = (is_null($article["title"]) ? "" : $article["title"]);
          $category = (is_null($article["category"]) ? "" : $article["category"]);
          $text = (is_null($article["text"]) ? "" : $article["text"]);
          $created = (is_null($article["created"]) ? "" : $article["created"]);
          echo "<div class='articlePreview'>\n";
          echo "<h4>".$title."</h4>\n";
          echo "<div class='articleCategory'>".$category."</div>\n";
          echo "<div class='articleText'>".$text."</div>\n";
          echo "<div class='articleCreated'>".$created."</div>\n";
          echo "</div>\n";
          $i++;
        }
      }
      echo "</div>\n";
    }
    echo "<input type='hidden' id='hf_subject' name='hf_subject' value='' />\n";
    echo "<input type='hidden' id='hf_msg' name='hf_msg' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function sendPrivateMessage($sender, $recipient, $subject, $message) {
    DBLogin("a8823305_audio");
    DBQuery("INSERT INTO private_messages (sender,recipient,subject,messageText,dateCreated) VALUES ('".$sender."','".$recipient."','".$subject."','".$message."',NOW())");
    DBC();
  }
  function getGroupById($id) {
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM groups WHERE id = ".$groupId.")");
    if ($result->num_rows > 0) {
      $result = $result->fetch_array(MYSQL_ASSOC);
    }
    return $result;
  }
  function getArticlesByCreator($creator) {
    $articles = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM articles WHERE creator = '".$creator."')");
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_array(MYSQL_ASSOC)) {
        $articles[] = $row;
      }
    }
    return $articles;
  }
  function getForumThreadsByCreator($creator) {
    $threads = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM forum_threads WHERE creator = '".$creator."')");
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_array(MYSQL_ASSOC)) {
        $threads[] = $row;
      }
    }
    return $threads;
  }
?>
</body>
</html>