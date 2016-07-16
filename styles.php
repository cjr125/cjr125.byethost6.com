<?php require 'db.php';
      $user = $_COOKIE["user"];
      $error = "";
      $client_id = $_GET["client_id"];
      
      $client_site_styles = getClientSiteStyles($client_id);
      foreach ($client_site_styles as $client_site_style) {
          echo $client_site_style["selector"]."{\n";
          $attributes = explode(";", $client_site_style["attributes"]);
          foreach ($attributes as $attribute) {
              echo $attribute.";\n";
          }
          echo "}\n";
      }
      function getClientSiteStyles($client_id) {
          $client_site_styles = array();
          DBLogin("a8823305_audio");
          $result = DBQuery("SELECT * FROM client_site_styles WHERE client_id = ".$client_id);
          if ($result->num_rows == 0) {
              echo "No rows found.";
              exit;
          }
          while ($row = $result->fetch_array(MYSQL_ASSOC)) {
              $client_site_styles[] = $row;
          }
          DBC();
          return $client_site_styles;
      }
?>