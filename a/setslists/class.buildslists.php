<?php
// Class to get and save Drop-Down Select Lists - http://coursesweb.net/
class buildSLists {
  // properties
  static protected $conn = false;            // stores the connection to mysql
  public $affected_rows = 0;        // number of affected, or returned rows in SQL query
  public $eror = false;          // to store and check for errors

  // constructor
  public function __construct() {
    // if required data are received
    if(isset($_POST['resname']) && isset($_POST['ajx'])) {
      $_POST = array_map('trim', $_POST);      // removes whitesapces

      // if $_POST['idop'] returns data from MySQL; else, if $_POST['sdata'], calls method to save data
      if(isset($_POST['idop'])) echo $this->getSlistsMysql($_POST['resname'], $_POST['idop']);   // required <select>
      else if(isset($_POST['geto'])) echo $this->allSlistsMysql($_POST['resname']);   // data for all <select>s
      else if(isset($_POST['sdata'])) echo $this->saveData();
    }
  }

  // to save Drop-Down Lists code
  public function saveData() {
    $re = '';
    // checks if correct Name and Password
    if(isset($_POST['admname']) && isset($_POST['admpass']) && $_POST['admname'] == ADMNAME && $_POST['admpass'] == ADMPASS) {
      if(isset($_POST['sdata']) && isset($_POST['admname']) && isset($_POST['admpass'])) {
        // calls the methods to save data, according to 'txt', 'mysql', 'txtmysql'
        if($_POST['saveto'] == 'txt') {
          if(file_put_contents('../slists/'. $_POST['resname'] .'.txt', $_POST['sdata'])) $re = 'Data saved in "'. $_POST['resname'] .'.txt"';
          else $re = 'Unable to save data in "'. $_POST['resname'] .'.txt"';
        }
        else if($_POST['saveto'] == 'mysql') {
          if($this->saveSListsMysql($_POST['resname'], $_POST['sdata'])) $re = ' Data saved in "'. $_POST['resname'] .'" table';
          else $re = ' Unable to save data in "'. $_POST['resname'] .'" table';
        }
        else if($_POST['saveto'] == 'txtmysql') {
          if(file_put_contents('../slists/'. $_POST['resname'] .'.txt', $_POST['sdata'])) $re = 'Data saved in "'. $_POST['resname'] .'.txt"';
          else $re = 'Unable to save data in "'. $_POST['resname'] .'.txt"';
          if($this->saveSListsMysql($_POST['resname'], $_POST['sdata'])) $re .= PHP_EOL .'Data saved in "'. $_POST['resname'] .'" table';
          else $re .= PHP_EOL .'Unable to save data in "'. $_POST['resname'] .'" table';
        }
      }
    }
    else $re = 'Incorrect Name or Password. Data Not saved.';

    return $re;
  }

  // for connecting to mysql
  protected function setConn() {
    try {
      // Connect and create the PDO object
      self::$conn = new PDO("mysql:host=".DBHOST."; dbname=".DBNAME, DBUSER, DBPASS);

      // Sets to handle the errors in the ERRMODE_EXCEPTION mode
      self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      self::$conn->exec('SET character_set_client="utf8",character_set_connection="utf8",character_set_results="utf8";');      // Sets encoding UTF-8
      
    }
    catch(PDOException $e) {
      $this->eror = 'Unable to connect to MySQL: '. $e->getMessage();
    }
  }

  // Performs SQL queries
  public function sqlExecute($sql) {
    if(self::$conn===false OR self::$conn===NULL) $this->setConn();      // sets the connection to mysql
    $re = true;           // the value to be returned

    // if there is a connection set ($conn property not false)
    if(self::$conn !== false) {
      // gets the first word in $sql, to determine whenb SELECT query
      $ar_mode = explode(' ', trim($sql), 2);
      $mode = strtolower($ar_mode[0]);

      // performs the query and get returned data
      try {
        if($sqlprep = self::$conn->prepare($sql)) {
          // execute query
          if($sqlprep->execute()) {
            // if $mode is 'select', gets the result_set to return
            if($mode == 'select' || $mode == 'show') {
              $re = array();
              // if fetch() returns at least one row (not false), adds the rows in $re for return
              if(($row = $sqlprep->fetch(PDO::FETCH_ASSOC)) !== false){
                do {
                  // check each column if it has numeric value, to convert it from "string"
                  foreach($row AS $k=>$v) {
                    if(is_numeric($v)) $row[$k] = $v + 0;
                  }
                  $re[] = $row;
                }
                while($row = $sqlprep->fetch(PDO::FETCH_ASSOC));
              }
              $this->affected_rows = count($re);                   // number of returned rows
            }
          }
          else $this->eror = 'Cannot execute the sql query';
        }
        else {
          $eror = self::$conn->errorInfo();
          $this->eror = 'Error: '. $eror[2];
        }
      }
      catch(PDOException $e) {
        $this->eror = $e->getMessage();
      }
    }

    // sets to return false in case of error
    if($this->eror !== false) {
      /// echo $this->eror;
      $re = false;
    }
    return $re;
  }

  // returns true if the table exists, else, false
  public function checkTable($table) {
    $resql = $this->sqlExecute("SHOW TABLES IN ". DBNAME ." WHERE `Tables_in_". DBNAME ."`='$table'");
    if($this->affected_rows > 0) return true;
    else return false;
  }

  // saves categories data in MySQL database, returns true or false
  public function saveSListsMysql($table, $sdata) {
    // if table exists, empty it, else, creates it
    if($this->checkTable($table) === true) $this->sqlExecute("DELETE FROM `$table`");
    else {
      $this->sqlExecute("CREATE TABLE `$table` (`id` INT UNSIGNED PRIMARY KEY, `value` CHAR(255) NOT NULL DEFAULT '', `content` VARCHAR(5000) NOT NULL DEFAULT '', `parent` INT NOT NULL DEFAULT 0) CHARACTER SET utf8 COLLATE utf8_general_ci");
    }

    $jsn = json_decode($sdata, true);

    // traverse the seccond array from $jsn, "optData", and define $values to be inserted
    $values = '';
    foreach($jsn[1] as $id=>$category) {
      $values .= '('. $id .", '". addslashes($category['value']) ."', '". addslashes($category['content']) ."', ". $category['parent'] ."),";
    }

    // insert $values, deleting last ','
    if($this->sqlExecute("INSERT INTO `$table` (`id`, `value`, `content`, `parent`) VALUES ". trim($values, ','))) return true;
    else return false;
  }

  // gets all Select Lists data from table, returns JSON with objects for $optHierarchy and $optData in JS
  public function allSlistsMysql($table) {
    $row = $this->sqlExecute("SELECT * FROM `$table`");

    // if rowa from SELECT, create two arrays with data for $optHierarchy and $optData in JS
    if($row && $this->affected_rows > 0) {
      $optHierarchy = array();  $optData = array();
      for($i=0; $i<$this->affected_rows; $i++) {
        $optData[$row[$i]['id']] = array('value'=>stripslashes($row[$i]['value']), 'content'=>stripslashes($row[$i]['content']), 'parent'=>$row[$i]['parent']);

        // Set $optHierarchy according to ID of the parent category
        $parent = $row[$i]['parent'];

        // if $parent not item in $optHierarchy, and not Root (id 0), create it, else, append the 'id'
        if(!isset($optHierarchy[$parent]) && $row[$i]['id'] > 0) $optHierarchy[$parent] = array($row[$i]['id']);
        else if($row[$i]['id'] > 0) $optHierarchy[$parent][] = $row[$i]['id'];
      }

      return json_encode(array($optHierarchy, $optData));    // returns the JSON string used in JS
    }
    else return 'Resource Not Found';
  }

  // returns JSON with 2 items: array with 'id_val' of child-options, and content of selected <option>
  public function getSlistsMysql($table, $idop) {
    $options = array('idv'=>array(), 'content'=>'');

    $row = $this->sqlExecute("SELECT e1.id, e1.value, e2.content FROM `$table` AS e1 INNER JOIN `$table` AS e2 ON `e2`.`id`=$idop WHERE `e1`.`parent`=$idop");

    // if rowa with option-childs from SELECT, adds data in $options, else, SELECT only for option-content
    if($row && $this->affected_rows > 0) {
      for($i=0; $i<$this->affected_rows; $i++) {
        $options['idv'][] = $row[$i]['id'] .'_'. stripslashes($row[$i]['value']);
      }
    }
    else {
      $row = $this->sqlExecute("SELECT content FROM `$table` WHERE `id`=$idop");
    }

    // if $row with data, add option-content and returns JSON, else, jeans not $table in database
    if($row && $this->affected_rows > 0) {
      $options['content'] = stripslashes($row[0]['content']);
       return json_encode($options);
    }
    else return 'Resource Not Found';
  }
}