<?php
  session_start();
  $mysqli = NULL;
  if ($_SESSION['connection'] && $_SESSION['connection'] != "") {
    $mysqli = $_SESSION['connection'];
  }
  function DBLogin($db_name) {
    $host = "localhost";
    $user = "root";
    $password = "";
    $mysqli = new mysqli($host, $user, $password, $db_name);
    if ($mysqli->connect_errno) {
      die('Not connected: '.$mysqli->connect_error);
    }
    $_SESSION['connection'] = $mysqli;
  }
  function DBQuery($qry) {
    if ($_SESSION['connection'] && $_SESSION['connection'] != "") {
      $mysqli = $_SESSION['connection'];
    }
    if (!$mysqli) {
      die('Error - Unable to connect to database');
    }
    $result = $mysqli->query($qry);
    if (!$result) {
      die('Error: '.$mysqli->error);
    }
    return $result;
  }
  function DBC() {
    if ($_SESSION['connection'] && $_SESSION['connection'] != "") {
      $mysqli = $_SESSION['connection'];
    }
    $mysqli->close(); 
  }
  function logged_in() {
    if ($_SESSION['connection'] && $_SESSION['connection'] != "") {
      $mysqli = $_SESSION['connection'];
    }
    $passwd = $_COOKIE["passwd"];
    if ($_COOKIE["user"] == null || $_COOKIE["user"] == "") {
      return false; 
    }
    else {
      DBLogin("a8823305_audio");
      $qry = "SELECT username,password FROM users WHERE username = '".$_COOKIE["user"]."'";
      $result = DBQuery($qry) or die("Invalid query: ".$mysqli->error);
      if ($result->num_rows > 0) {
        $result = $result->fetch_row();
        $db_password = $result["password"];
        if ($passwd == $db_password) {
          DBC();
          DBQuery("UPDATE users SET lastLogin = NOW() WHERE username = '".$_COOKIE["user"]."'");
          return true;
        }
        else {
          echo "Invalid Username/Password";
          DBC();
          return false;
        }
      }
      echo "Invalid Username/Password";
      DBC();
      return false;
    }
  }
?>

