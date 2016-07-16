<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript">
	function getUrlVars() {
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++) {
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars;
	}
	function createXMLHTTPRequest() {
		XMLHttpRequest.prototype.baseOpen = XMLHttpRequest.prototype.open;
		XMLHttpRequest.prototype.open = function (method, url, async) {
			this._url = url;
			return XMLHttpRequest.prototype.baseOpen.apply(this, arguments);
		};
		var xmlhttp;
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else {// code for IE6, IE5
			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		}
		return xmlhttp;
	}
	window.onload = function () {
		var contentID = getUrlVars()["ContentID"];
		if (contentID != undefined) {
			var req = createXMLHTTPRequest();
			req.onreadystatechange = function () {
				if (req.readyState == 4 && req.status == 200) {
					document.write(req.responseText);
				}
			}
			req.open("GET", "http://localhost:60023/ContentFilters.aspx?ContentID=" + contentID, true);
			req.send();
		}
	}
</script>
</head>
</html>