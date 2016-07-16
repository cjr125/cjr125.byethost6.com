<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
      $user = $_COOKIE["user"];
      $error = "";
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#btn_submit").on("click", function() {
            clearHiddenFields();
            $("#hf_email_preferences").val($("#rb_email_preferences input[type=radio]:checked"]).val());
            $("#hf_playlist_skin").val($("#rb_playlist_skin input[type=radio]:checked"]).val());
            $("#hf_ouput_bitrate").val($("#rb_output_bitrate input[type=radio]:checked"]).val());
            $("#hf_output_format").val($("#rb_output_format input[type=radio]:checked"]).val());
            $("#form1").submit();
        }
        function clearHiddenFields() {
            $("#hf_email_preferences").val("");
            $("#hf_playlist_skin").val("");
            $("#hf_output_format").val("");
            $("#hf_ouput_bitrate").val("");
        }
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
        echo "<form id='form1' name='form1' action='settings.php' method='post'>\n";
        echo "<table id='tbl_settings'>\n";
        echo "<tr><th>settings</th><th>description</th></th>options</th></tr>\n";
        $settings = getSettingsForUser($username);
        foreach ($settings as $setting) {
            $id = $setting["id"];
            $name = $setting["name"];
            $description = $setting["description"];
            $selected_options = explode(",",$setting["selected_options"]);
            $modified = $setting["modified"];
            echo "<tr><td>".$name."</td><td>".$description."</td><td>\n";
            echo "<div id='rb_playlist_skin'><input type='radio' name='rb_playlist_skin' value='skin0'".($selected_options[0] == "skin0" ? "checked" : "")."><image alt='skin0' src='img/skin0.jpg' />\n";
            echo "<input type='radio' name='rb_playlist_skin' value='skin1'".($selected_options[0] == "skin1" ? "checked" : "")."><image alt='skin1' src='img/skin1.jpg' />\n";
            echo "<input type='radio' name='rb_playlist_skin' value='skin2'".($selected_options[0] == "skin2" ? "checked" : "")."><image alt='skin2' src='img/skin2.jpg' /></div>\n";
            echo "<div id='rb_output_bitrate'><input type='radio' name='rb_output_bitrate' value='128'".($selected_options[1] == "128" ? "checked" : "").">128 KB/s\n";
            echo "<input type='radio' name='rb_output_bitrate' value='64'".($selected_options[1] == "64" ? "checked" : "").">64 KB/s\n";
            echo "<input type='radio' name='rb_output_bitrate' vaue='32'".($selected_options[1] == "32" ? "checked" : "").">32 KB/s</div>\n";
            echo "<div id='rb_output_format'><input type='radio' name='rb_output_format' value='mp3'".($selected_options[2] == "mp3" ? "checked" : "").">mp3\n";
            echo "<input type='radio' name='rb_output_format' value='wav'".($selected_options[2] == "wav" ? "checked" : "").">wav\n";
            echo "<input type='radio' name='rb_output_format' value='wma'".($selected_options[2] == "wma" ? "checked" : "").">wma</div></td></tr>\n";
        }
        echo "</table>\n";
        echo "</form>\n";
    } else {
        header("Location: cms.php");
    }
    function getSettingsForUser($username) {
        $settings = array();
        DBLogin("a8823305_audio");
        $result = DBQuery("SELECT * FROM settings WHERE username = '".$username."'");
        if ($result->num_rows == 0) {
            echo "No rows found.";
            exit;
        }
        while ($row = $result->fetch_array(MYSQL_ASSOC)) {
            $settings[] = $row;
        }
        DBC();
        return $settings;
    }
?>
</body>
</html>
