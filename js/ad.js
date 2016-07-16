(function(a) {
  var li_1 = document.getElementsByTagName("li")[0];
  var li = document.createElement("li");
  var span = document.createElement("span");
  var audio = document.createElement("audio");
  var source = document.createElement("source");
	
  source.src="http://localhost/audio/*.mp3";
  source.type="audio/mpeg";
  span.className="trackName";
  audio.controls="controls";
  audio.appendChild(source);
  li.appendChild(span);
  li.appendChild(audio);
	
  document.getElementsByTagName("ul")[0].insertBefore(li,li_1);
        
  var title=source.src.substring(source.src.lastIndexOf("/")+1,source.src.indexOf(".mp3"));
  span.appendChild(document.createTextNode(source.src.substring(source.src.lastIndexOf("/")+1,source.src.indexOf(".mp3"))));
  document.getElementById(a).insertBefore(li,document.getElementsByTagName("li")[0]);
})("#spotId")

var uri = document.getElementsByTagName("script")[1].src;
var spotId = getParamaterByName("spotId");
if(spotId != null && spotId !== "") {
  var adO = {
    imgUrls:["/images/default.jpg"],
    imgAlts:["default"],
    index:0
  };
  var creative = new Image(300,250);
  creative.src = adO.imgUrls[adO.index];
  creative.alt = adO.imgAlts[adO.index];
  var li = document.createElement("li");
  li.appendChild(creative);
  document.getElementsByTagName("ul")[0].insertBefore(li,document.getElementsByTagName("li")[0]);
}

function getParamaterByName(name) {
  var endIndex = uri.indexOf("&");
  if (endIndex == -1) {
    endIndex = uri.length;
  } 
  return uri.substr(uri.indexOf(name+"=")+name.length+1,endIndex);
}

