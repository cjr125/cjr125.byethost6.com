<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  require 'HtmlUtil.php';

  $user = $_COOKIE["user"];
  $users = array();
  $groupName = $_GET["group"];
  $messageText = "";
  $recipient = ""; 
  $subject = "";
  $selected_private_msg = "";
  $privateMessages = getPrivateMessages($user);
  $sentMessages = getSentPrivateMessages($user);
  $chatText = "";

  if (isset($_POST["hf_open_private_msg"]) && $_POST["hf_open_private_msg"] != "") {
    $rowIndex = intval($_POST["hf_open_private_msg"]);
    $messageText = $privateMessages[$rowIndex]["messageText"];
    $subject = $privateMessages[$rowIndex]["subject"];
    $selected_private_msg = "<table id='tbl_private_message'>
                                    <tr><td><label for='tb_recipient'>To:</label><input type='text' id='tb_recipient' name='tb_recipient' value='".$recipient."' /></td></tr>
                                    <tr><td><label for='tb_subject'>Subject:</label><input type='text' id='tb_subject' name=tb_subject' value='".$subject."' /></td></tr>
                                    <tr><td><textarea id='messageText' name='messageText' rows='40' cols='60'>".$messageText."</textarea></td></tr>
                                  </table>";
  }
  if (isset($_POST["hf_add_friend"]) && $_POST["hf_add_friend"] != "") {
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT friendListId FROM users WHERE username = '".$user."'");
    if ($result->num_rows > 0) {
      $userId = intval($_POST["hf_add_friend"]);
      $friendListId = $result->fetch_array(MYSQL_ASSOC)["friendListId"];
      if ($userId > 0 && $friendListId > 0) {
        addUserToFriendlist($userId, $friendListId);
      }
    }
  }
  if (isset($_POST["hf_invite_to_group"]) && $_POST["hf_invite_to_group"] != "") {
    $subscriber = $_POST["hf_invite_to_group"];
    $link = <a href=\"group.php?subscriber=".$subscriber."\">Join ".$groupName."</a>";
    if (isset($_POST["subscriber_pw"]) && $_POST["subscriber_pw"] != "") {
      $link = <a href=\"javascript:document.cookie[\"subscriber_pw\"]=".sha1($_POST["subscriber_pw"])."group.php?subscriber=".$subscriber."\">Join ".$groupName."</a>";
    }
    sendPrivateMessage($user, $subscriber, $user." wants to invite you to join ".$groupName, "Click the following link to ".$link);
  }
  if (isset($_POST["hf_delete"]) && $_POST["hf_delete"] != "") {
    $rowIndexes = explode(",",$_POST["hf_delete"]);
    $privateMessageIndexes = "";
    foreach ($rowIndexes as $rowIndex) {
      $privateMessageIndexes .= ($privateMessageIndexes == "" ? $privateMessages[$rowIndex]["id"] : ",".$privateMessages[$rowIndex]["id"]);
    }
    if ($privateMessageIndexes != "") {
      DBLogin("a8823305_audio");
      DBQuery("DELETE FROM private_messages WHERE id IN (".$privateMessageIndexes.")");
      DBC();
    }
  }
  if (isset($_POST["hf_private_message_text"]) && $_POST["hf_private_message_text"] != "") {
    $messageText = $_POST["hf_private_message_text"];
    if (isset($_POST["hf_recipient"]) && $_POST["hf_recipient"] != "") {
      $recipient = $_POST["hf_recipient"];
      if (isset($_POST["hf_subject"]) && $_POST["hf_subject"] != "") {
        $subject = $_POST["hf_subject"];
        sendPrivateMessage($user, $recipient, $subject, $messageText);
      }
    }
  }
  if (isset($_POST["hf_chat_text"]) && $_POST["hf_chat_text"] != "") {
    $chatText = $_POST["hf_chat_text"];
  }
  if (isset($_POST["btn_enter"])) {
    if ($chatText != "") {
      DBLogin("a8823305_audio");
      if (isset($groupName) && $groupName != "") {
        DBQuery("INSERT INTO community (groupName,chatText,dateCreated,lastUpdated) VALUES ('".$groupName."','".$chatText."',NOW(),NOW())");
      } else {
        DBQuery("UPDATE community SET chatText = '".$user.": ".$chatText."', lastUpdated = NOW() WHERE groupName = 'default'");
      }
      DBC();
    }
  }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#newMessage").on("click", function() {
      clearHiddenFields();
      $(".privateMessages").html("<table id='tbl_private_message'>
                                    <tr><td><label for='tb_recipient'>To:</label><input type='text' id='tb_recipient' name='tb_recipient' value='<?php echo $recipient; ?>' /></td></tr>
                                    <tr><td><label for='tb_subject'>Subject:</label><input type='text' id='tb_subject' name=tb_subject' value='<?php echo $subject; ?>' /></td></tr>
                                    <tr><td><textarea id='messageText' name='messageText' rows='40' cols='60'><?php echo $messageText; ?></textarea></td></tr>
                                  </table>");
    });
    $(".privateMessage").on("click", function() {
      clearHiddenFields();
      $("#hf_open_private_msg").val($(".privateMessage").index(this));
      $("#form1").submit();
    });
    $("#sendMessage").on("click", function() {
      clearHiddenFields();
      $("#hf_recipient").val($("#tb_recipient").val());
      $("#hf_subject").val($("#tb_subject").val());
      $("#hf_private_message_text").val($("#messageText").val());
      alert("Message Sent!");
      $("#form1").submit();
    });
    $("#selectAllMessages").on("click", function() {
      clearHiddenFields();
      if ($("#selectAllMessages").html() == "Select All")) {
        for (i = 0; i < <?php echo sizeof($privateMessages); ?>; i++) {
          $(".privateMessages #cb_delete")[i].setAttribute("checked", "checked");
        }
        $("#selectAllMessages").html("Deselect All");
      } else {
        for (i = 0; i < <?php echo sizeof($privateMessages); ?>; i++) {
          $(".privateMessages #cb_delete")[i].setAttribute("checked", "unchecked");
        }
        $("#selectAllMessages").html("Select All");
      }
      $("#form1").submit();
    });
    $("#deleteMessages").on("click", function() {
      clearHiddenFields();
      for (i = 0; i < <?php echo sizeof($privateMessages); ?>; i++) {
        if ($(".privateMessages #cb_delete")[i].is(":checked")) {
          $("#hf_delete").val(($("#hf_delete").val() == "" ? i : ","+i);
        } 
      }
      $("#form1").submit();
    });
    $("#sentMessages").on("click", function() {
      clearHiddenFields();
      $(".privateMessages").html("<div class='privateMessage'>
                              <?php foreach ($sentMessages as $sentMessage) {
                                      $recipient = $sentMessage["recipient"];
                                      $subject = $sentMessage["subject"];
                                      $messageText = $sentMessage["messageText"];
                                      $dateCreated = $sentMessage["dateCreated"];
                                      echo "<div class='delete'><input type='checkbox' id='cb_delete' name='cb_delete' checked='unchecked' /></div>\n";
                                      echo "<div class='created'>".$dateCreated."</div><div class='recipient'>To: ".$recipient."</div><div class='subject'>Subject: ".$subject."</div>\n";
                                      echo "<div class='message'>".$messageText."</div>\n"; ?>
                                  </div>");
    });
    $("#btn_enter").on("click", function() {
      clearHiddenFields();
      $("#hf_chat_text").val($("#tb_chat").val());
      $("#form1").submit();
    });
    $("#btn_add_friend").on("click", function() {
      clearHiddenFields();
      $("#hf_add_friend").val(
    function clearHiddenFields() {
      $("#hf_open_private_msg").val("");
      $("#hf_add_friend").val("");
      $("#hf_private_message_text").val("");
      $("#hf_chat_text").val("");
    }
  });
</script>
<style type="text/css">
  .privateMessageContainer {
    width:750px;
    height:500px;
    margin:0 auto;
  }
  .privateMessageToolbar {
    width:730px;
    height:100px;
    margin:0 auto;
  }
  .chatTextContainer {
    width:500px;
    height:500px;
    margin:0 auto;
  }
  .chatInputContainer {
    width:500px;
    height:30px;
    margin:0 auto;
  }
  #tb_chat {
    float:left;
    width:420px;
  }
  #btn_enter {
    float:right;
  }
</head>
<body>
<?php
  if (logged_in()) {
    echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    echo "<form id='form1' name='form1' action='community.php' method='post'>\n";
    echo "<div class='privateMessageContainer'>\n";
    echo "<div class='privateMessageToolbar'><ul><li id='newMessage'>Compose</li><li id='sendMessage'>Send</li><li id='selectAllMessages'>Select All</li><li id='deleteMessages'>Delete</li><li id='sentMessages'>Sent</li></ul></div>\n";
    echo "<div class='privateMessages'>\n";
    foreach ($privateMessages as $privateMessage) {
      $sender = $privateMessage["sender"];
      $subject = $privateMessage["subject"];
      $messageText = $privateMessage["messageText"];
      $dateCreated = $privateMessage["dateCreated"];
      echo "<div class='privateMessage'>\n";
      echo "<div class='delete'><input type='checkbox' id='cb_delete' name='cb_delete' checked='unchecked' /></div>\n";
      echo "<div class='created'>".$dateCreated."</div><div class='sender'>From: ".$sender."</div><div class='subject'>Subject: ".$subject."</div>\n";
      echo "<div class='message'>".$messageText."</div>\n";
      echo "</div>\n";
    }
    if (isset($_POST["hf_open_private_msg"]) && $_POST["hf_open_private_msg"] != "") {
      echo $selected_private_msg;
    }
    echo "</div>\n";
    echo "</div>\n"
    echo "<div class='chatTextContainer'>\n";
    echo $chatText;
    echo "</div>\n";
    echo "<div class='chatInputContainer'>\n";
    echo "<input type='text' id='tb_chat' name='tb_chat' value='".$tb_chat."' /><input type='button' id='btn_enter' name='btn_enter' value='Enter' />\n";
    echo "</div>\n";
    echo "<div class='chatUserList'>\n";
    $users = getDefaultCommunityUsers();
    foreach ($users as $usr) {
      echo "<div class='chatUserContainer'>".$usr["username"]." <input type='button' id='btn_add_friend' name='btn_add_friend' value='Add To Friends' />";
      if (isset($groupName) && $groupName != "") {
        echo " <input type='button' id='btn_invite_to_group' name='btn_invite_to_group' value='' />\n";
      }
      echo "</div>\n";
    }
    echo "</div>\n";
    echo "<input type='hidden' id='hf_open_private_msg' name='hf_open_private_msg' value='' />\n";
    echo "<input type='hidden' id='hf_add_friend' name='hf_add_friend' value='' />\n";
    echo "<input type='hidden' id='hf_invite_to_group' name='hf_invite_to_group' value='' />\n";
    echo "<input type='hidden' id='hf_delete' name='hf_delete' value='' />\n";
    echo "<input type='hidden' id='hf_recipient' name='hf_recipient' value='' />\n";
    echo "<input type='hidden' id='hf_subject' name='hf_subject' value='' />\n";
    echo "<input type='hidden' id='hf_private_message_text' name='private_message_text' value='' />\n";
    echo "<input type='hidden' id='hf_chat_text' name='hf_chat_text' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getDefaultCommunity() {
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM community ORDER BY dateCreated ASC LIMIT 1");
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    $result = $result->fetch_array(MYSQL_ASSOC);
    DBC();
    return $result;
  }
  function getDefaultCommunityUsers() {
    DBLogin("a8823305_audio");
    $users = array();
    $result = DBQuery("SELECT username FROM users WHERE groupId = 1");
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC) {
      $users[] = $row;
    }
    DBC();
    return $users;
  }
  function getCommunityByGroupName($groupName) {
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM community WHERE groupName = '".$groupName."'");
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    $result = $result->fetch_array(MYSQL_ASSOC);
    DBC();
    return $result;
  }
  function getPrivateMessages($username) {
    $messages = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM private_messages WHERE recipient = '".$username."'");
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $messages[] = $row;
    }
    DBC();
    return $messages;
  }
  function getSentPrivateMessages($username) {
    $messages = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM private_messages WHERE sender = '".$username."'");
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $messages[] = $row;
    }
    DBC();
    return $messages;
  }
  function sendPrivateMessage($sender, $recipient, $subject, $message) {
    DBLogin("a8823305_audio");
    DBQuery("INSERT INTO private_messages (sender,recipient,subject,messageText,dateCreated) VALUES ('".$sender."','".$recipient."','".$subject."','".$message."',NOW())");
    DBC();
  }
  function addUserToFriendlist($userId, $friendlistId) {
    DBLogin("a8823305_audio");
    DBQuery("INSERT INTO friendlists (id,userId,dateAdded) VALUES(".$friendlistId.",".$userId.",NOW())");
    DBC();
  }
?>
</body>
</html>