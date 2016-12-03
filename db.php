<?php
  session_start();
  $connection = NULL;
  if ($_SESSION['connection'] && $_SESSION['connection'] != "") {
    $connection = $_SESSION['connection'];
  }
  function DBLogin($db_name) {
    $host = "mysql13.000webhost.com";
    $user = "a8823305_cjr125";
    $password = "________";
    $connection = mysql_connect($host, $user, $password);
    if (!$connection) {
      die('Not connected: '.mysql_error());
    }
    $db = mysql_select_db($db_name);
    $_SESSION['connection'] = $connection;
    $_COOKIE['user'] = $user;
    $_COOKIE['passwd'] = $password;
  }
  function DBQuery($qry) {
    if ($_SESSION['connection'] && $_SESSION['connection'] != "") {
      $connection = $_SESSION['connection'];
    }
    if (!$connection) {
      die('Error - Unable to connect to database');
    }
    $result = mysql_query($qry, $connection);
    if (!$result) {
      die('Error: '.mysql_error());
    }
    return $result;
  }
  function DBC() {
    if ($_SESSION['connection'] && $_SESSION['connection'] != "") {
      $connection = $_SESSION['connection'];
    }
    mysql_close($connection); 
  }
  function mysql_evaluate($query, $default_value="undefined") {
    $result = mysql_query($query);
    if (mysql_num_rows($result)==0)
        return $default_value;
    else
        return mysql_result($result,0);
  }
  function mysql_evaluate_array($query) {
    $result = mysql_query($query);
    $values = array();
    for ($i=0; $i<mysql_num_rows($result); ++$i)
        array_push($values, mysql_result($result,$i));
    return $values;
  }
  function logged_in() {
    if ($_SESSION['connection'] && $_SESSION['connection'] != "") {
      $connection = $_SESSION['connection'];
    }
    $passwd = $_COOKIE["passwd"];
    if ($_COOKIE["user"] == null || $_COOKIE["user"] == "") {
      return false; 
    }
    else {
      DBLogin("a8823305_audio");
      $qry = "SELECT username,password FROM users WHERE username = '".$_COOKIE["user"]."'";
      $result = DBQuery($qry) or die("Invalid query: ".mysql_error());
      if (mysql_num_rows($result) > 0) {
        $result = mysql_fetch_row($result);
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


