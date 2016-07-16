<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
    $user = $_COOKIE["user"];
    $error = "";
    $client_id = $_GET["client_id"];
    $track_name = "";
    $track_duration = "";
    $track_url = "";
    
    if (isset($_POST["hf_track_name"]) && $_POST["hf_track_name"] != "") { 
        $track_name = $_POST["hf_track_name"];
    }
    if (isset($_POST["hf_track_duration"]) && $_POST["hf_track_duration"] != "") {
        $track_duration = $_POST["hf_track_duration"];
    }
    if (isset($_POST["hf_track_url"]) && $_POST["hf_track_url"] != "") {
        $track_url = $_POST["hf_track_url"];
    }
    if (isset($_POST["btn_submit"])) {
        if ($track_url != "" && $track_duration != "" && $track_name != "") {
            DBLogin("a8823305_audio");
            DBQuery("INSERT INTO repertoire (track_name,track_duration,track_url) VALUES ('".$track_name."','".$track_duration."','".$track_url."')");
            DBC();
        }
    }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#btn_submit").on("click", function() {
            $("#hf_track_name").val($("#tb_track_name").val());
            $("#hf_track_duration").val($("#tb_track_duration").val());
            $("#hf_track_url").val($("#tb_track_url").val())
            $("#form1").submit();
        });
    });
</script>
</head>
<body>
<?php
    if (logged_in()) {
        echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
        include 'menu_bar.php';
        if ($error != "") {
            echo "<span class='error'>".$error."</span><br>\n";
        }
        echo "<form id='form1' name='form1' action='repertoire.php' method='post'>\n";
        echo "<tr><th>track name</th><th>duration</th><th>url</th></tr>\n";
        $repertoire = getRepertoire($client_id);
        foreach ($repertoire as $track) {
            $name = $track["name"];
            $duration = $track["duration"];
            $url = $track["url"];
            echo "<tr><td>".$name."</td><td>".$duration."</td><td>".$url."</td></tr>\n";
        }
        echo "<tr><td colspan='2'></td><td><input type='button' id='btn_submit' name='btn_submit' value='Submit' /></td></tr>\n";
        echo "<table>\n";
        echo "<input type='hidden' id='hf_track_name' name='hf_track_name' value='' />\n";
        echo "<input type='hidden' id='hf_track_duration' name='hf_track_duration' value='' />\n";
        echo "<input type='hidden' id='hf_track_url' name='hf_track_url' value='' />\n";
        echo "</form>\n";
    } else {
        header("Location: cms.php");
    }
    function getRepertoire($client_id) {
        $repertoire = array();
        DBLogin("a8823305_audio");
        $result = DBQuery("SELECT * FROM repertoire WHERE client_id = ".$client_id);
        if ($result->num_rows == 0) {
            echo "No rows found.";
            exit;
        }
        while ($row = $result->fetch_array(MYSQL_ASSOC)) {
            $repertoire[] = $row;
        }
        DBC();
        return $repertoire;
    }
?>
</body>
</html>