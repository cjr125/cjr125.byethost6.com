<?php 
header('content-type: application/json; charset=utf-8'); 
header("access-control-allow-origin: *");

include 'db.php';

$sample = null;

function getSampleById(sampleID) {
  DBLogin('a8823305_audio');
  $sample = DBQuery('SELECT * FROM cjrmusic WHERE id = '.sampleID.';');
  DBC();
}

if ($_GET['sid'] != null) {
  getSampleById($_GET['sid']);
  echo $_GET['callback'] . '('.json_encode($sample).')';
}

?>		