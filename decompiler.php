<?php

error_reporting(E_ALL);
define('HOST', 'api.showmycode.com');
define('ENDPOINT', 'api/');
define('WS_KEY', '');
define('SECRET', '');

// Getting encoded file contents:
$contents = getFileContents('/home/liudvikas/Documents/SocketServer_test.exe');

// Sending request:
$response = callRemote($contents);

// Decoded file:
echo $response['data'];



//** You do not need to edit bellow **//


function getFileContents($path)
{
  $filename = basename($path);
  $contents = file_get_contents($path);
  if ($contents != FALSE)
  {
  	return array(
  	             'filename'=>base64_encode($filename),
  	             'contents'=>base64_encode($contents)
  	             );
  }
}

function createMessageSig($contents,$secret)
{
  ksort($contents);
  $data = '';
  foreach ($contents as $key => $value)
  {
    $data .= "$key{$value}";
  }
  $sig = hash_hmac('md5', $data, $secret);
  return $sig;
}

function callRemote($contents)
{
  $url = sprintf('http://%s/%s?wsKey=%s&sig=%s', HOST, ENDPOINT,
    WS_KEY, createMessageSig($contents, SECRET));

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $contents);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 4);
  $result = curl_exec($curl);

  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  curl_close($curl);

  return array('http' => $httpCode, 'data' => $result);
}
?>