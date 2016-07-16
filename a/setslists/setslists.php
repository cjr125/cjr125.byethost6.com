<?php
// Script Multiple Drop-Down Select Lists Creator - http://coursesweb.net/

// HERE add your Admin data, to can save the menu
define('ADMNAME', 'name');
define('ADMPASS', 'password');

// HERE add your data for connecting to MySQL database (MySQL server, user, password, database name)
define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', 'passdb');
define('DBNAME', 'dbname');

     /* From Here no need to modify */

if(!headers_sent()) header('Content-type: text/html; charset=utf-8');      // header for utf-8

// Include and uses the class that gets and saves drop-down lists data
include('class.buildslists.php');
$obSLists = new buildSLists();