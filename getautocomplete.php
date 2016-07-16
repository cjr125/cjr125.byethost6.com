<?php require 'db.php';
 DBLogin("a8823305_audio");
 
 $term = $_GET["term"];
 
 $result = DBQuery("SELECT * FROM media WHERE content LIKE '%".$term."%' OR keywords LIKE '%".$term."%' OR url LIKE '%".$term."%' ORDER BY id ASC");
 $json = array();
 while($media = $result->fetch_array(MYSQLI_ASSOC)){
   $json[] = array(
     'contentValue' => $media["content"],
     'contentLabel' => $media["content"]." - ".$media["id"],
     'keywordValue' => $media["keywords"],
     'keywordLabel' => $media["keywords"]." - ".$media["id"],
     'urlValue' => $media["url"],
     'urlLabel' => $media["url"]." - ".$media["id"]
   );
 }
 DBC();
 
 echo json_encode($json);
 
?>