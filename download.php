<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
    require 'HtmlUtil.php';
    $user = $_COOKIE["user"];
    $songs = createDropdown(",",implode(",",getAllSongs()),"dd_songs","");
    $selected_songs = "";
    if (isset($_POST["hf_selected_songs"]) && $_POST["hf_selected_songs"] != "") {
        $selected_songs = $_POST["hf_selected_songs"];
    }
    if (isset($_POST["btn_download"])) {
        if ($selected_songs != "") {
            
        }
    }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#dd_songs").change(function() {
            var selected_songs = $("#hf_selected_songs").val();
            if (selected_songs) {
                selected_songs += (selected_songs != "" ? "," : "") + $("#dd_songs:selected").val();
            }
            $("#hf_selected_songs").val(selected_songs);
        });
        $("#btn_download").on("click", function() {
            clearHiddenFields();
            $("#form1").submit();
        });
        function clearHiddenFields() {
            $("#hf_selected_songs").val("");
        }
    });
</script>
</head>
<body>
<?php
if (logged_in()) {
    echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    echo "<form id='form1' name='form1' action='download.php' method='post'>\n";
    echo "<table id='tbl_download'>\n";
    echo "<tr><th>select tracks</th><th>selected tracks</th><th></th></tr>\n";
    echo "<tr><td>".$songs."</td><td>".$selected_songs."</td><td><input type='button' id='btn_download' name='btn_download' value='Download' /></td></tr>\n";
    echo "</table>\n";
    echo "<input type='hidden' id='hf_selected_songs' name='hf_selected_songs' value='' />\n";
    echo "</form>\n";
} else {
    header("Location: cms.php");
}
function getAllSongs() {
    $songs = array();
    DBLogin("a8823305_audio");
    $SQL = "SELECT * FROM songs";
    $result = DBQuery($SQL);
    if ($result->num_rows == 0) {
        echo "No rows found";
        exit;
    }
    while ($row = $result->fetch_array(MYSQL_ASSOC)) {
        $songs[] = $row;
    }
    return $songs;
}
?>
</body>
</html>