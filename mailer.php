<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  $("#submit").on("click", function() {
    sendMail(document.getElementById('address').value, document.getElementById('cc').value, document.getElementById('subject').value);
    $("#form1").submit();
  });
});
function sendMail(address, cc, subject) {
  var link = "mailto:" + address
             + "?cc=" + cc
             + "&subject=" + escape(subject)
             + "&body=" + escape(document.getElementById('emailText').value);
  window.location.href = link;
}
</script>
</head>
<body>
<form id='form1' name='form1' action='mailer.php' method='post'>
<label for='address'>Address:</label>
<input type='text' id='address' name='address' /><br>
<label for='cc'>CC:</label>
<input type='text' id='cc' name='cc' style='margin-left:31px;' /><br>
<label for='subject'>Subject:</label>
<input type='text' id='subject' name='subject' style='margin-left:7px;' /><br>
<textarea id="emailText" style='margin-left:50px;'>
    Lorem ipsum...
</textarea>
<input type='submit' id='submit' name='submit' value='SEND' /><br>
</form>
</body>
</html>