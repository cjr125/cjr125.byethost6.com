<!DOCTYPE html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/jquery.autocomplete.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
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
    $("#tbl_users th.col").on("click", function() {
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
    $("#btn_submit").on("click", function() {
      clearHiddenFields();
      $("#form1").submit();
    });
    $("#btn_delete_all").on("click", function() {
      clearHiddenFields();
      $("#hf_delete").val("delete");
      $("#form1").submit();
    });
    function clearHiddenFields() {
      $("#hf_delete").val("");
      $("#hf_search").val("");
    }
  });
</script>
<style type='text/css'>
  #tbl_users th {
    padding-right:15px;
    background: url('images/icon_down_sort_arrow.png') no-repeat center right;
  }
  #tbl_users th.asc {
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
    DBQuery("DELETE FROM users WHERE 1=1");
    DBC();
  }
  if (logged_in()) {
    echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    echo "<form id='form1' name='form1' action='users.php' method='post'>\n";
    echo "<table id='tbl_search'>\n";
    echo "<tr><td>Search:</td><td><input type='text' id='tb_search' name='tb_search' value='".($search != "" ? $search : "Search Users")."'></input></td>".
             "<td><input type='button' id='btn_search' name='btn_search' value='Search'></input></td></tr>\n";
    echo "</table>\n";
    echo "<table id='tbl_users' border='1'>\n";
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
    echo "<tr><th class='col".($sort == "first" ? $direction : "")."'>first</th><th class='col".($sort == "last" ? $direction : "")."'>last</th><th class='col".($sort == "username" ? $direction : "")."'>username</th><th class='col".($sort == "email" ? $direction : "")."'>email</th><th class='col".($sort == "confirmationCode" ? $direction : "")."'>confirmationCode</th><th class='col".($sort == "groupId" ? $direction : "")."'>groupId</th><th class='col".($sort == "accountStatus" ? $direction : "")."'>accountStatus</th><th class='col".($sort == "registrationDate" ? $direction : "")."'>registrationDate</th><th class='col".($sort == "lastLogin" ? $direction : "")."'>lastLogin</th></tr>\n";
    $emailAddresses = getRegisteredUsers($sort);
    foreach ($users as $row) {
      $first = $row["first"];
      $last = $row["last"];
      $username = $row["username"];
      $email = $row["email"];
      $confirmationCode = $row["confirmationCode"];
      $groupId = intval($row["groupId"]);
      $accountStatus = $row["accountStatus"];
      $registrationDate = $row["registrationDate"];
      $lastLogin = $row["lastLogin"];
      if ($search == "" || (strpos($username, $search) != -1) || strpos($email, $search) != -1)) {
        echo "<tr><td>".$first."</td><td>".$last."</td><td>".$username."</td><td>".$email."</td><td>".$confirmationCode."</td><td>".$groupId."</td><td>".$accountStatus."</td><td>".$registrationDate."</td><td>".$lastLogin."</td></tr>\n";
      }
    }
    echo "<tr><td><input type='button' id='btn_delete_all' name='btn_delete_all' value='Delete All' /></td><td><input type='button' id='btn_submit' name='btn_submit' value='Submit' /></td></tr>\n";
    echo "</table>\n";
    echo "<input type='hidden' id='hf_delete' name='hf_delete' value='' />\n";
    echo "<input type='hidden' id='hf_search' name='hf_search' value='' />\n";
    echo "<input type='hidden' id='hf_sort' name='hf_sort' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getRegisteredUsers($sort) {
    $users = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT * FROM users".($sort == "" ? "" : " ORDER BY ".$sort));
    if (num_rows > 0) {
      while($row = $result->fetch_row()) {
        $users[] = $row;
      }
    } else {
      echo "No rows found.";
      exit;
    }
    DBC();
    return $users;
  }
  function getFavoriteSongsByUserId($id) {
    $favoriteSongTitles = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT title FROM cjrmusic JOIN favoriteslists ON id = songId WHERE userId = ".$id);
    if (num_rows > 0) {
      while($row = $result->fetch_row()) {
        $favoriteSongTitles[] = $row["title"];
      }
    } else {
      echo "No rows found.";
      exit;
    }
    DBC();
    return $favoriteSongTitles;
  }
  function getFavoriteAlbumsByUserId($id) {
    $favoriteAlbumTitles = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT title FROM albums JOIN favoriteslists ON id = albumId WHERE userId = ".$id);
    if (num_rows > 0) {
      while($row = $result->fetch_row()) {
        $favoriteAlbumTitles[] = $row["title"];
      }
    } else {
      echo "No rows found.";
      exit;
    }
    DBC();
    return $favoriteAlbumTitles;
  }
  function getFavoriteArtistsByUserId($id) {
    $favoriteArtists = array();
    DBLogin("a8823305_audio");
    $result = DBQuery("SELECT name FROM artists JOIN favoriteslists ON id = artistId WHERE userId = ".$id);
    if (num_rows > 0) {
      while($row = $result->fetch_row()) {
        $favoriteArtists[] = $row["title"];
      }
    } else {
      echo "No rows found.";
      exit;
    }
    DBC();
    return $favoriteArtists;
  }
?>
</body>
</html>