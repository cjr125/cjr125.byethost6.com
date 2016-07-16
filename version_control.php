<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
function update() {
  sessionStorage._content = document.getElementById("textarea1").value;
}
function submit() {
  document.form1.submit();
}
function clear() {
  document.getElementById("textarea1").value = "";
}
</script>
</head>
<body>
<form id="form1" action="version_control.php" method="post">
<textarea id="textarea1" name="textarea1"></textarea><br>
<input type="button" id="btn_update" name="btn_update" value="Update" onclick="javascript:update()">
<input type="button" id="btn_submit" name="btn_submit" value="Submit" onclick="javascript:submit()">
<input type="button" id="btn_clear" name="btn_clear" value="Clear" onclick="javascript:clear()">
</form>
</body>
</html>			