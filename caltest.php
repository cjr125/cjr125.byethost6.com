<?php 


include("class_calendar.php"); 

$isPostBack = false;

$referer = "";
$thisPage = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = substr($_SERVER['HTTP_REFERER'],0,strpos($_SERVER['HTTP_REFERER'], "?"));
}

if ($referer == $thisPage) {
    $isPostBack = true;
} 

// Make objects 

$Cal = new Calendar("caltest.php");    // set page-name 

if (!$isPostBack) {
    $Cal->Day = date("d");            // example - set current date 
    $Cal->Month = date("m"); 
    $Cal->Year = date("Y");
}
else {
    $Cal->Day = $_GET['ChosenDay'];
    $Cal->Month = $_GET['ChosenMonth'];
    $Cal->Year = $_GET['ChosenYear'];
}

// User Interface 

$Cal->Show(); 


?> 