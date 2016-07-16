<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
      $user = $_COOKIE["user"];
      $error = "";
      $client_id = $_GET["client_id"];
      $selected_documentation = "";
      
      if (isset($_POST["hf_selected_documentation"]) && $_POST["hf_selected_documentation"] != "") {
          $selected_documentation = $_POST["hf_selected_documentation"];
      }
      if (isset($_POST["btn_submit"])) {
          if ($selected_documentation != "") {
              DBLogin("a8823305_audio");
              DBQuery("DELETE FROM documentation WHERE client_id = ".$client_id);
              $selected_documentation = explode(",", $selected_documentation);
              foreach ($selected_documentation as $selected_documentation_row) {
                  $documentation_hash = explode(":", $selected_documentation_row);
                  $documentation_id = $documentation_hash[0];
                  $documentation_name = $documentation_hash[1];
                  $documentation_parameter = $documentation_hash[2];
                  $documentation_value = $documentation_hash[3];
                  $created = $documentation_hash[4];
                  DBQuery("INSERT INTO documentation (client_id,documentation_id,documentation_name,documentation_parameter,documentation_value,created,modified) VALUES (".$client_id.",".$documentation_id.",'".$documentation_name."','".$documentation_parameter."','".$documentation_value."','".$created."',NOW())");
              }
              DBC();
          }
      }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#btn_submit").on("click", function() {
            var selected_documentation = "";
            for (var i = 0; i < $("#cb_select").length; i++) {
                if ($("#cb_select")[i].prop(":checked")) {
                    var documentation_hash = $(".documentationId")[i].html() + ":" + $("#tb_documentation_name")[i].val() + ":" + $("#tb_documentation_parameter")[i].val() + ":" + $("#tb_documentation_value")[i].val() + ":" + $(".created")[i].html();
                    selected_documentation += (selected_documentation == "" ? documentation_hash : "," + documentation_hash);
                }
            }
            $("#hf_selected_documentation").val(selected_documentation);
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
        echo "<form id='form1' name='form1' action='documentation.php' method='post'>\n";
        echo "<table id='tbl_documentation'>\n";
        echo "<tr><th style='display:none;'></th><th>documentation name</th><th>documentation parameter</th><th>documentation value</th><th>created</th><th>modified</th><th>select</th></tr>\n";
        $documentation = getDocumentation($client_id);
        foreach ($documentation as $documentation_row) {
            $documentation_id = $documentation_row["id"];
            $documentation_name = $documentation_row["name"];
            $documentation_parameter = $documentation_row["parameter"];
            $documentation_value = $documentation_row["value"];
            $created = $documentation_row["created"];
            $modified = $documentation_row["modified"];
            $selected = $documentation_row["selected"];
            echo "<tr><td class='documentationId'>".$documentation_id."</td>\n";
            echo "<td><input type='text' id='tb_documentation_name' name='tb_documentation_name' value='".$documentation_name."' /></td>\n";
            echo "<td><input type='text' id='tb_documentation_parameter' name='tb_documentation_parameter' value='".$documentation_parameter."' /></td>\n";
            echo "<td><textarea id='tb_documentation_value' name='tb_documentation_value'>".$documentation_value."</textarea></td>\n";
            echo "<td class='created'>".$created."</td><td>".$modified."</td>\n";
            echo "<td><input type='checkbox' id='cb_select' name='cb_select' checked='".($selected ? "checked" : "unchecked")."' /></td></tr>\n";
        }
        echo "<tr><td><input type='button' id='btn_submit' name='btn_submit' value='Submit' /></td><td colspan='6'></td></tr>\n";
        echo "</table>\n";
        echo "<input type='hidden' id='hf_selected_documentation' name='hf_selected_documentation' value='' />\n";
        echo "</form>\n";
    } else {
        header("Location: cms.php");
    }
    function getDocumentation($client_id) {
        $documentation = array();
        DBLogin("a8823305_audio");
        $result = DBQuery("SELECT * FROM documentation WHERE client_id = ".$client_id);
        if ($result->num_rows == 0) {
            echo "No rows found.";
            exit;
        }
        while ($row = $result->fetch_array(MYSQL_ASSOC)) {
            $documentation[] = $row;
        }
        DBC();
        return $documentation;
    }
?>
</body>
</html>