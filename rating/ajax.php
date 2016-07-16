<?php
require_once 'config.php';

    if($_POST['act'] == 'rate'){
    	//search if the user(ip) has already gave a note
    	$ip = $_SERVER["REMOTE_ADDR"];
    	$therating = $_POST['rate'];
    	$thepost = $_POST['post_id'];

    	$query = mysql_query("SELECT * FROM ratings where ip= '$ip' and id_post = '$thepost'"); 
    	while($data = mysql_fetch_assoc($query)){
    		$rating_db[] = $data;
    	}

    	if(@count($rating_db) == 0 ){
    		mysql_query("INSERT INTO ratings (id_post, ip, rating)VALUES('$thepost', '$ip', '$therating')");
    	}else{
    		mysql_query("UPDATE ratings SET rating= '$therating' WHERE ip = '$ip' AND id_post = '$thepost'");
    	}
    } 
?>
