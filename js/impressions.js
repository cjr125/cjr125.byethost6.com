var starttime,eventId,mediaId,eventType,created;

$(document).ready(function() {
  var d = new Date();
  starttime = d.getTime();
  eventId = getUrlVars()["eventId"];
  mediaId = getUrlVars()["mediaId"];
  created = new Date();
  created = created.getTime();
  eventType = getUrlVars()["eventType"];
  $(window).unload(function() {
    d = new Date();
    endtime = d.getTime();
    $.ajax({ 
      url: "time.php",
      data: {'time': endtime - starttime,
             'eventId': eventId,
             'mediaId': mediaId,
             'eventType': eventType,
             'created': created}
    });
  });
}
function getUrlVars() {
  var vars = {};
  var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
  function(m,key,value) {
    vars[key] = value;
  });
  return vars;
}
