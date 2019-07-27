<?php
  session_start();
  $connection = NULL;
  if ($_SESSION['connection'] && $_SESSION['connection'] != "") {
    $connection = $_SESSION['connection'];
  }
  function DBLogin($db_name) {
    $host = "localhost";
    $user = "root";
    $password = "";
    $connection = mysqli_connect($host, $user, $password);
    if (!$connection) {
      die('Not connected: '.mysqli_error($connection));
    }
    $db = mysqli_select_db($connection, $db_name);
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
    $result = mysqli_query($connection, $qry);
    if (!$result) {
      die('Error: '.mysqli_error($connection));
    }
    return $result;
  }
  function DBC() {
    if ($_SESSION['connection'] && $_SESSION['connection'] != "") {
      $connection = $_SESSION['connection'];
    }
    mysqli_close($connection); 
  }
  function mysql_evaluate($query, $default_value="undefined") {
    $result = mysqli_query($connection, $query);
    if (mysql_num_rows($result)==0)
        return $default_value;
    else
        return mysql_result($result,0);
  }
  function mysql_evaluate_array($query) {
    $result = mysqli_query($connection, $query);
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
      DBLogin("b6_17569910_audio");
      $qry = "SELECT username,password FROM users WHERE username = '".$_COOKIE["user"]."'";
      $result = DBQuery($qry) or die("Invalid query: ".mysqli_error($connection));
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
