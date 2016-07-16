<!DOCTYPE html>
<head>
<?php require 'db.php';
      $user = $_COOKIE["user"];
      $error = "";
      $client_id = $_GET["client_id"];
      $selected_references = "";
      
      if (isset($_POST["hf_selected_references"]) && $_POST["hf_selected references"] != "") {
          $selected_references = $_POST["hf_selected_references"];
      }
      if (isset($_POST["btn_submit"])) {
          if ($selected_references != "") {
              DBLogin("a8823305_audio");
              DBQuery("DELETE FROM references WHERE client_id = ".$client_id);
              $selected_references = explode(",", $selected_references);
              foreach ($selected_references as $selected_reference) {
                  $reference_hash = explode(":", $selected_reference);
                  $reference_id = $reference_hash[0];
                  $reference_name = $reference_hash[1];
                  $reference_relationship = $reference_hash[2];
                  $reference_significance = $reference_hash[3];
                  DBQuery("INSERT INTO references (client_id,reference_id,reference_name,reference_relationship,reference_significance) VALUES (".$client_id.",".$reference_id.",'".$reference_name."','".$reference_relationship."','".$reference_significance."')");
              }
              DBC();
          }
      }
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#btn_submit").on("click", function() {
            var selected_references = "";
            for (var i = 0; i < $("#cb_select").length; i++) {
                if ($("#cb_select")[i].prop(":checked")) {
                    var reference_hash = $(".referenceId")[i].html() + ":" + $("#tb_reference_name")[i].val() + ":" + $("#tb_reference_relationship")[i].val() + ":" + $("#tb_reference_significance")[i].val();
                    selected_references += (selected_references == "" ? reference_hash : "," + reference_hash);
                }
            }
            $("#hf_selected_references").val(selected_references);
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
        echo "<form id='form1' name='form1' action='references.php' method='post'>\n";
        echo "<table id='tbl_references'>\n";
        echo "<tr><th style='display:none;'></th><th>reference name</th><th>reference relationship</th><th>reference significance</th></tr>\n";
        $references = getReferences($client_id);
        foreach ($references as $reference) {
            $reference_id = $reference["id"];
            $reference_name = $reference["name"];
            $reference_relationship = $reference["relationship"];
            $reference_significance = $reference["significance"];
            echo "<tr><td class='referenceId'>".$reference_id."</td>\n":
            echo "<td><input type='text' id='tb_reference_name' name='tb_reference_name' value='".$reference_name."' /></td>\n";
            echo "<td><input type='text' id='tb_reference_relationship' name='tb_reference_relationship' value='".$reference_relatinoship."' /></td>\n";
            echo "<td><textarea id='tb_reference_significance' name='tb_reference_significance'>".$reference_significance."</textarea></td>\n";
            echo "<td><input type='checkbox' id='cb_select' name='cb_select' checked='".($selected ? "checked" : "unchecked")."' /></td></tr>\n";
        }
        echo "<tr><td><input type='button' id='btn_submit' name='btn_submit' value='Submit' /></td><td colspan'3'></td></tr>\n";
        echo "</table>\n";
        echo "<input type='hidden' id='hf_selected_references' name='hf_selected_references' value='' />\n";
        echo "</form>\n";
    } else {
        header("Location: cms.php");
    }
    function getReferences($client_id) {
        $references = array();
        DBLogin("a8823305_audio");
        $result = DBQuery("SELECT * FROM references WHERE client_id = ".$client_id);
        if ($result->num_rows == 0) {
            echo "No rows found.";
            exit;
        }
        while ($row = $result->fetch_array(MYSQL_ASSOC)) {
            $references[] = $row;
        }
        DBC();
        return $references;
    }
?>
</body>
</html>