<?php
class myClass {
  function __autoload($classname) {
    $filename = "./". $classname .".php";
    include_once($filename);
    $obj = new myClass();
  }
  function __construct() {
    echo "myClass init'ed successfuly!!!";
  }
}
?>		