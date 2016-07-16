<?php require 'db.php';
$time = (isset($_GET['time']) ? $_GET['time'] : 0);
if ($time > 0) {
    DBLogin("a8823305_audio");
    DBQuery("INSERT INTO events (eventId,mediaId,eventType,created,eventDuration) VALUES (".$_GET['eventId'].",".$_GET['mediaId'].",".$_GET['eventType'].",".$_GET['created'].",".$time.")");
    DBC();
}
?>