<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  require 'HtmlUtil.php';
  
  $user = $_COOKIE["user"];
  $error = "";
  $category = "";
  $thread = "";
  $subject = "";
  $text = "";
  $dateCreated = new DateTime("0000-00-00 00:00:00");
  if (isset($_POST["hf_category"]) && $_POST["hf_category"] != "") {
    $category = $_POST["hf_category"];
  }
  if (isset($_POST["hf_thread"]) && $_POST["hf_thread"] != "") {
    $thread = $_POST["hf_thread"];
  }
  if (isset($_POST["hf_subject"]) && $_POST["hf_subject"] != "") {
    $subject = $_POST["hf_subject"];
  }
  if (isset($_POST["hf_text"]) && $_POST["hf_text"] != "") {
    $text = $_POST["hf_text"];
  }
  if (isset($_POST["btn_submit"])) {
    if ($text != "" && $subject != "" && $thread != "" && $category != "") {
      $dateCreated = new DateTime("NOW");
      DBLogin("a8823305_audio");
      DBQuery("INSERT INTO forum_posts (category,thread,subject,text,dateCreated) VALUES ('".$category."','".$thread."','".$subject."','".$text."','".$dateCreated."')");
      DBC();
    }
  }
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
    $("#tbl_forum th.col").on("click", function() {
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
      $("#form1").submit();
    });
  });
</script>
<style type='text/css'>
  #tbl_forum th {
    padding-right:15px;
    background: url('images/icon_down_sort_arrow.png') no-repeat center right;
  }
  #tbl_forum th.asc {
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
  if (logged_in()) {
    echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    if ($error != "") {
      echo "<span class='error'>".$error."</span><br>\n";
    }
    echo "<form id='form1' name='form1' action='forum.php'  method='post'>\n";
    echo "<table id='tbl_search'>\n";
    echo "<tr><td>Search:</td><td><input type='text' id='tb_search' name='tb_search' value='".($search != "" ? $search : "Search Keywords")."'></input></td>".
             "<td><input type='button' id='btn_search' name='btn_search' value='Search'></input></td></tr>\n";
    echo "</table>\n";
    echo "<table id='tbl_forum'>\n";
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
    echo "<tr><th class='col".($sort == "name" ? $direction : "")."'>name</th><th class='col".($sort == "category" ? $direction : "")."'>category</th><th class='col".($sort == "description" ? $direction : "")."'>description</th><th class='col".($sort == "latestPost" ? $direction : "")."'>latestPost</th><th class='col".($sort == "numPosts" ? $direction : "")."'>numPosts</th></tr>\n";
    $categories = getAllForumCategories($sort);
    foreach ($categories as $category) {
      $name = $category["name"];
      $forumCategory = $category["category"];
      $description = $category["description"];
      $latestPost = $category["latestPost"];
      $numPosts = $category["numPosts"];
      $dateCreated = $category["dateCreated"];
      if ($search == "" || ((strpos($name, $search) != -1) || (strpos($forumCategory, $search) != -1) || (strpos($description, $search) != -1) || (strpos($latestPost, $search) != -1)) {
        echo "<tr><td>".$name."</td><td>".$forumCategory."</td><td>".$description."</td><td>".$latestPost."</td><td>".$numPosts."</td><td>".$dateCreated."</td></tr>\n";
      }
    }
    echo "</table>\n";
    echo "<input type='button' id='btn_submit' name='btn_submit' value='Submit' />\n";
    echo "<input type='hidden' id='hf_delete' name='hf_delete' value='' />\n";
    echo "<input type='hidden' id='hf_search' name='hf_search' value='' />\n";
    echo "<input type='hidden' id='hf_sort' name='hf_sort' value='' />\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
  function getAllForumCategories($sort) {
    $categories = array();
    DBLogin("a8823305_audio");
    $SQL = "SELECT * FROM forum_categories";
    if ($sort != "") {
      $SQL .= " ORDER BY ".$sort;
    }
    $result = DBQuery($SQL);
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $categories[] = $row;
    }
    DBC();
    return $categories;
  }
  function getForumPostsByThreadName($nme, $sort) {
   $posts = array();
   DBLogin("a8823305_audio");
   $SQL = "SELECT * FROM forum_posts WHERE thread = '".$name."'";
   if ($sort != "") {
     $SQL .= " ORDER BY ".$sort;
   }
   $result = DBQuery($SQL);
   if ($result->num_rows == 0) {
     echo "No rows found";
     exit;
   }
   while ($row = $result->fetch_array(MYSQL_ASSOC)) {
     $posts[] = $row;
   }
   return $posts;
  }
  function getForumPostsByCategory($category, $sort) {
    $posts = array();
    DBLogin("a8823305_audio");
    $SQL = "SELECT * FROM forum_posts WHERE category = '".$category."')";
    if ($sort != "") {
       $SQL .= " ORDER BY ".$sort;
    }
    $result = DBQuery($SQL);
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $posts[] = $row;
    }
    return $posts;
  }
  function getForumThreadsByCategory($category, $sort) {
    $threads = array();
    DBLogin("a8823305_audio");
    $SQL = "SELECT * FROM forum_threads WHERE category = '".$category."')";
    if ($sort != "") {
       $SQL .= " ORDER BY ".$sort;
    }
    $result = DBQuery($SQL);
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $threads[] = $row;
    }
    return $threads;
  }
  function getForumPostsByCreator($creator, $sort) {
    $posts = array();
    DBLogin("a8823305_audio");
    $SQL = "SELECT * FROM forum_posts WHERE creator = '".$creator."')";
    if ($sort != "") {
      $SQL .= " ORDER BY ".$sort;
    }
    $result = DBQuery($SQL);
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $posts[] = $row;
    }
    return $posts;
  }
  function getForumThreadsByCreator($creator, $sort) {
    $threads = array();
    DBLogin("a8823305_audio");
    $SQL = "SELECT * FROM forum_threads WHERE creator = '".$creator."')";
    if ($sort != "") {
      $SQL .= " ORDER BY ".$sort;
    }
    $result = DBQuery($SQL);
    if ($result->num_rows == 0) {
      echo "No rows found";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $threads[] = $row;
    }
    return $threads;
  }
  function getFeaturedThreads($sort) {
    $threads = array();
    DBLogin("a8823305_audio");
    $SQL = "SELECT * FROM forum_threads WHERE featured = 1";
    if ($sort != "") {
      $SQL .= " ORDER BY ".$sort;
    }
    $result = DBQuery($SQL);
    if ($result->num_rows == 0) {
      echo "No rows found.";
      exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
      $threads[] = $row;
    }
    return $threads;
  }
?>
</body>
</html>