<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  require 'HtmlUtil.php';
  $user = $_COOKIE["user"];
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/jquery.autocomplete.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#tb_search").on("click", function() {
      if ($("#tb_search").val() == "Search Keywords") {
        $("#tb_search").val("");
      }
    });
    $("#tb_search").autocomplete({
      serviceUrl:'getautocomplete.php',
      minLength:1
    });
    $("#btn_search").on("click", function() {
      clearHiddenFields();
      $("#hf_search").val($("#tb_search").val());
      $("#form1").submit();
    });
    $("#tbl_songs th.col").on("click", function() {
      clearHiddenFields();
      var direction = "DESC";
      if (this.classList.contains("asc")) {
        this.classList.remove("asc");
      } else {
        direction = "ASC";
        this.classList.add("asc");
      }
      $("#hf_sort").val(this.innerHTML + " " + direction);
      $("#form1").submit();
    });
    $("#btn_delete").on("click", function() {
      clearHiddenFields();
      $("#hf_delete").val("delete");
      $("#form1").submit();
    });
    function clearHiddenFields() {
      $("#hf_delete").val("");
      $("#hf_search").val("");
      $("#hf_sort").val("");
    }
  });
</script>
<style type='text/css'>
  #tbl_songs th {
    padding-right:15px;
    background: url('images/icon_down_sort_arrow.png') no-repeat center right;
  }
  #tbl_songs th.asc {
    background: url('images/icon_up_sort_arrow.png') no-repeat center right;
  }
  .autocomplete-suggestions {
    border: 1px solid black;
  }
</style>
</head>
<body>
<?php
  $search = "";
  if (isset($_POST["hf_search"]) && $_POST["hf_search"] != "") {
    $search = $_POST["hf_search"];
  }
  $sort = "";
  if (isset($_POST["hf_sort"]) && $_POST["hf_sort"] != "") {
    $sort = $_POST["hf_sort"];
  }
  if (isset($_POST["hf_delete"]) && $_POST["hf_delete"] == "delete") {
    DBLogin("a8823305_audio"); 
    DBQuery("DELETE FROM songs WHERE 1=1");
    DBC();
  }
  if (logged_in()) {
    echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    echo "<form id='form1' name='form1' action='songs.php' method='post'>\n";
    echo "<table id='tbl_search'>\n";
    echo "<tr><td>Search:</td><td><input type='text' id='tb_search' name='tb_search' value='".($search != "" ? $search : "Search Keywords")."'></input></td>".
             "<td><input type='button' id='btn_search' name='btn_search' value='Search'></input></td></tr>\n";
    echo "</table>\n";
    echo "<table id='tbl_songs'>\n";
    $direction = "";
    if ($sort != "") {
      $length = strpos($sort, " ");
      $temp = $sort;
      $sort = substr($sort, 0, $length);
      $direction = substr($temp, $length+1);
      if ($direction == "ASC") {
        $direction = " asc";
      } else {
        $direction = "";
      }
    }
    echo "<tr><th class='col".($sort == "lyrics" ? $direction : "")."'>lyrics</th><th class='col".($sort == "instruments" ? $direction : "")."'>instruments</th><th class='col".($sort == "title" ? $direction : "")."'>title</th><th class='col".($sort == "creator" ? $direction : "")."'>creator</th><th class='col".($sort == "numFollowers" ? $direction : "")."'>numFollowers</th><th class='col".($sort == "price" ? $direction : "")."'>price</th><th class='col".($sort == "description" ? $direction : "")."'>description</th><th class='col".($sort == "duration" ? $direction : "")."'>duration</th><th class='col".($sort == "explicit" ? $direction : "")."'>explicit</th><th class='col".($sort == "location" ? $direction : "")."'>location</th><th class='col".($sort == "key" ? $direction : "")."'>key</th><th class='col".($sort == "bpm" ? $direction : "")."'>bpm</th></tr>\n";
    $songs = getAllSongs($sort);
    foreach ($songs as $song) {
      $lyrics = $song["lyrics"];
      $instruments = $song["instruments"];
      $title = $song["title"];
      $creator = $song["creator"];
      $numFollowers = intval($song["numFollowers"]);
      $price = $song["price"];
      $description = $song["description"];
      $duration = $song["duration"];
      $explicit = $song["explicit"];
      $location = $song["location"];
      $key = $song["key"];
      $bpm = $song["bpm"];
      if ($search == "" || ((strpos($lyrics, $search) != -1) || (strpos($instruments, $search) != -1) || (strpos($title, $search) != -1) || (strpos($creator, $search) != -1) || (strpos($description, $search) != -1) || (strpos($location, $search) != -1)) {
        $instruments = createDropdown(",",$instruments,"dd_instruments","");
        echo "<tr><td>".$lyrics."</td><td>".$instruments."</td><td>".$title."</td><td>".creator."</td><td>".$numFollowers."</td><td>".$price."</td><td>".$description."</td><td>".$duration."</td><td>".$explicit."</td><td>".$location."</td><td>".$key."</td><td>".$bpm."</td></tr>\n";
      }
    }
    echo "<tr><td colspan='8'></td><td><input type='button' id='btn_delete' name='btn_delete' value='Delete All' /></td></tr>\n";
    echo "</table>\n";
    echo "<input type='hidden' id='hf_delete' name='hf_delete' value='' />\n";
    echo "<input type='hidden' id='hf_search' name='hf_search' value='' />\n";
    echo "<input type='hidden' id='hf_sort' name='hf_sort' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getAllSongs($sort) {
    $songs = array();
    DBLogin("a8823305_audio");
    $SQL = "SELECT * FROM songs";
    if ($sort != "") {
      $SQL .= " ORDER BY ".$sort;
    }
    $result = DBQuery($SQL);
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $songs[] = $row;
    }
    DBC();
    return $songs;
  }
?>
</body>
</html>