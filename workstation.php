<!DOCTYPE html>
<html>
<head>
<?php require 'db.php';
  $user = $_COOKIE["user"];

  if (isset($_COOKIE["page"]) && $_COOKIE["page"] != "workstation.php") {
    header("Location: ".$_COOKIE["page"]);
  }
  setcookie('page', 'workstation.php');
?>
</head>
<body>
<?php
  if (logged_in()) {
    echo "Logged in as ".$user.". <a href=\"logout.php\">Logout</a><br>\n";
    include 'menu_bar.php';
    echo "<form id='form1' name='form1' action='workstation.php' method='post'>\n";
    echo "<script src=\"https://www.java.com/js/deployJava.js\" type=\"text/javascript\">\n";
    echo "</script><script type=\"text/javascript\">\n";
    echo "//<![CDATA[
          var attributes = { archive: 'http://www.cjr125.netai.net/DAW/src/Workstation.jar',
                       codebase: 'http://www.cjr125.netai.net/DAW/src',
                       code:'components.Workstation', width:'800', height:'600' };
          var parameters = { permissions:'sandbox', nimgs:'9', offset:'0',
                       img: '../images', maxwidth:'455' };
          deployJava.runApplet(attributes, parameters, '1.7');
          //]]>\n";
    echo "</script><noscript>A browser with Javascript enabled is required for this page to operate properly.</noscript>\n";
    echo "</form>\n";
  } else {
    header("Location: cms.php");
  }
?>
</body>
</html>