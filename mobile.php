<!DOCTYPE html>
<html>
<head>
  <?php 
    define('BASEPATH',realpath('.'));
    require 'db.php'; 
    $referer = $_SERVER["HTTP_REFERER"];                                                         
    if ($referer != "") {                                                                        
      DBLogin("a8823305_audio");
      $result = DBQuery("INSERT INTO referers (url, uri, remote_addr, useragent) VALUES('".$referer."', '".$_SERVER["REQUEST_URI"]."', '".$_SERVER["REMOTE_ADDR"]."', '".$_SERVER["HTTP_USER_AGENT"]."')");
      DBC();
    }
  ?>

  <title>Music</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link rel="stylesheet" type="text/css" href="css/jPlayer.css" />
  <link rel="stylesheet" type="text/css" href="css/prettify-jPlayer.css" />
  <link rel="stylesheet" type="text/css" href="skin/blue.monday/jplayer.blue.monday.css" />
  <link rel="stylesheet" type="text/css" href="css/common.css" />
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script type="text/javascript" src="js/jquery.jplayer.min.js"></script>
  <script type="text/javascript">
  var jPlayerAndroidFix = (function($) {
	var fix = function(id, media, options) {
		this.playFix = false;
		this.init(id, media, options);
	};
	fix.prototype = {
		init: function(id, media, options) {
			var self = this;

			// Store the params
			this.id = id;
			this.media = media;
			this.options = options;

			// Make a jQuery selector of the id, for use by the jPlayer instance.
			this.player = $(this.id);

			// Make the ready event to set the media to initiate.
			this.player.bind($.jPlayer.event.ready, function(event) {
				// Use this fix's setMedia() method.
				self.setMedia(self.media);
			});

			// Apply Android fixes
			if($.jPlayer.platform.android) {

				// Fix playing new media immediately after setMedia.
				this.player.bind($.jPlayer.event.progress, function(event) {
					if(self.playFixRequired) {
						self.playFixRequired = false;

						// Enable the contols again
						// self.player.jPlayer('option', 'cssSelectorAncestor', self.cssSelectorAncestor);

						// Play if required, otherwise it will wait for the normal GUI input.
						if(self.playFix) {
							self.playFix = false;
							$(this).jPlayer("play");
						}
					}
				});
				// Fix missing ended events.
				this.player.bind($.jPlayer.event.ended, function(event) {
					if(self.endedFix) {
						self.endedFix = false;
						setTimeout(function() {
							self.setMedia(self.media);
						},0);
						// what if it was looping?
					}
				});
				this.player.bind($.jPlayer.event.pause, function(event) {
					if(self.endedFix) {
						var remaining = event.jPlayer.status.duration - event.jPlayer.status.currentTime;
						if(event.jPlayer.status.currentTime === 0 || remaining < 1) {
							// Trigger the ended event from inside jplayer instance.
							setTimeout(function() {
								self.jPlayer._trigger($.jPlayer.event.ended);
							},0);
						}
					}
				});
			}

			// Instance jPlayer
			this.player.jPlayer(this.options);

			// Store a local copy of the jPlayer instance's object
			this.jPlayer = this.player.data('jPlayer');

			// Store the real cssSelectorAncestor being used.
			this.cssSelectorAncestor = this.player.jPlayer('option', 'cssSelectorAncestor');

			// Apply Android fixes
			this.resetAndroid();

			return this;
		},
		setMedia: function(media) {
			this.media = media;

			// Apply Android fixes
			this.resetAndroid();

			// Set the media
			this.player.jPlayer("setMedia", this.media);
			return this;
		},
		play: function() {
			// Apply Android fixes
			if($.jPlayer.platform.android && this.playFixRequired) {
				// Apply Android play fix, if it is required.
				this.playFix = true;
			} else {
				// Other browsers play it, as does Android if the fix is no longer required.
				this.player.jPlayer("play");
			}
		},
		resetAndroid: function() {
			// Apply Android fixes
			if($.jPlayer.platform.android) {
				this.playFix = false;
				this.playFixRequired = true;
				this.endedFix = true;
				// Disable the controls
				// this.player.jPlayer('option', 'cssSelectorAncestor', '#NeverFoundDisabled');
			}
		}
	};
	return fix;
  })(jQuery);
  //<![CDATA[
  $(document).ready(function(){
  var id, media, options;
  <?php
        $folder = opendir("audio/");
        $i = 0;
        while (($file = readdir($folder)) !== false) {
          if ($file != "." && $file != "..") {
		$name = substr($file, 0, strpos($file, "."));
		if ($name != "" && substr($file, strlen($file)-3) == "mp3") {
		      echo "id = \"#jquery_jplayer_".$i."\";\n";
                      echo "media = {\n";
                      echo "mp3: \"http://".$_SERVER["HTTP_HOST"]."/smartReadFile.php?filename=".$file."\"";
                      if (file_exists("audio/".$name.".ogg")) {
                              echo ",\noga: \"http://".$_SERVER["HTTP_HOST"]."/smartReadFile.php?filename=".$name.".ogg\"};\n";
                      }
                      else {
                              echo "};\n";
                      }
                      echo "options = {\n";
                      echo "swfPath: \"http://".$_SERVER["HTTP_HOST"]."/js\",\n";
		      echo "supplied: \"";
                      if (file_exists("audio/".$name.".ogg")) {
                              echo "oga, ";
                      }
                      echo "mp3\",\n";
		      echo "cssSelectorAncestor: \"#jp_container_".$i."\",\n";
		      echo "wmode: \"window\",\n";
		      echo "smoothPlayBar: true,\n";
		      echo "keyEnabled: true\n";
		      echo "};\n";
                      echo "$(id).jPlayer({\n";
		      echo "ready: function (event) {\n";
		      echo "$(this).jPlayer(\"setMedia\", media);\n";
                      echo "},\n";
		      echo "swfPath: options.swfPath,\n";
                      echo "supplied: options.supplied,\n";
                      echo "cssSelectorAncestor: options.cssSelectorAncestor,\n";
                      echo "wmode: options.wmode,\n";
                      echo "smoothPlayBar: options.smoothPlayBar,\n";
                      echo "keyEnabled: options.keyEnabled});\n";
                      echo "var myAndroidFix = new jPlayerAndroidFix(\"#jquery_jplayer_".$i."\", media, options);\n";
                      $i++;
              	}
          }
        }
        closedir($folder);
  ?>
  });
  //]]>
  </script>
  <script type="text/javascript">
  (function() {
	var s = document.createElement('script'), t = document.getElementsByTagName('script')[0];
	s.type = 'text/javascript';
	s.async = true;
	s.src = 'http://api.flattr.com/js/0.6/load.js?mode=auto';
	t.parentNode.insertBefore(s, t);
  })();
  </script>
</head>
<body onload="prettyPrint();">
  <div class="container">
    <h1>Music</h1>
    <div class="overlay"></div>
    <ul id="#spotId">
      <?php
	$folder = opendir("audio/");
        DBLogin("a8823305_audio");
	$result = DBQuery("SELECT title,duration FROM cjrmusic");
	$durations = array();
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$durations[$row["title"]] = substr($row["duration"],0,5);
                if (strpos($durations[$row["title"]],"0") === 0) {
                        $durations[$row["title"]] = substr($durations[$row["title"]],1);
                }
	}
	DBC();
	$i = 0;
        while (($file = readdir($folder)) !== false) {
          if ($file != "." && $file != "..") {
            $name = substr($file, 0, strpos($file, "."));
	    if ($name != "" && array_key_exists($name, $durations)) {
              echo "<li><div id=\"jquery_jplayer_".$i."\" class=\"jp-jplayer\" style=\"width:0px;height:0px;\"></div>\n";
              echo "<div id=\"jp_container_".$i."\" class=\"jp-audio\" style=\"margin:0 auto;\">\n";
              echo "<div class=\"jp-type-single\">\n";
              echo "<div class=\"jp-gui jp-interface\">\n";
              echo "<ul class=\"jp-controls\">\n";
              echo "<li><a href=\"javascript:;\" class=\"jp-play\" tabindex=\"1\">play</a></li>\n";
              echo "<li><a href=\"javascript:;\" class=\"jp-pause\" tabindex=\"1\" style=\"display:none;\">pause</a></li>\n";
              echo "<li><a href=\"javascript:;\" class=\"jp-stop\" tabindex=\"1\">stop</a></li>\n";
              echo "<li><a href=\"javascript:;\" class=\"jp-mute\" tabindex=\"1\" title=\"mute\">mute</a></li>\n";
              echo "<li><a href=\"javascript:;\" class=\"jp-unmute\" tabindex=\"1\" title=\"unmute\">unmute</a></li>\n";
              echo "<li><a href=\"javascript:;\" class=\"jp-volume-max\" tabindex=\"1\" title=\"max volume\">max volume</a></li>\n";
              echo "</ul>\n";
              echo "<div class=\"jp-progress\">\n";
              echo "<div class=\"jp-seek-bar\" style=\"width:100%;\">\n";
              echo "<div class=\"jp-play-bar\" style=\"width:0%;\"></div>\n";
              echo "</div>\n";
              echo "</div>\n";
              echo "<div class=\"jp-volume-bar\">\n";
              echo "<div class=\"jp-volume-bar-value\" style=\"width:80%;\"></div>\n";
              echo "</div>\n";
              echo "<div class=\"jp-time-holder\">\n";
              echo "<div class=\"jp-current-time\">0:00</div>\n";
              echo "<div class=\"jp-duration\">".$durations[$name]."</div>\n";
              echo "<ul class=\"jp-toggles\">\n";
              echo "<li><a href=\"javascript:;\" class=\"jp-repeat\" tabindex=\"1\" title=\"repeat\">repeat</a></li>\n";
              echo "<li><a href=\"javascript:;\" class=\"jp-repeat-off\" tabindex=\"1\" title=\"repeat off\" style=\"display:none\">repeat off</a></li>\n";
              echo "</ul>\n";
              echo "</div>\n";
              echo "</div>\n";
              echo "<div class=\"jp-title\">\n";
              echo "<ul>\n";
              echo "<li>".$name."</li>\n";
              echo "</ul>\n";
              echo "</div>\n";
              echo "<div class=\"jp-no-solution\">\n";
              echo "<span>Update Required</span>\n";
              echo "To play the media you will need to either update your browser to a recent version or update your <a href=\"http://get.adobe.com/flashplayer/\" target=\"_blank\">Flash plugin</a>.";
              echo "</div>\n";
              echo "</div>\n";
              echo "</div></li>\n";
            }
          }
          $i++;
        }
        closedir($folder);
      ?>
    </ul>
    <script type="text/javascript" src="js/prettify-jPlayer.js"></script>
    <!--<script type="text/javascript" src="js/ad.js"></script>-->
    <div style="display:none;">
    <!-- START OF HIT COUNTER CODE -->
    <br><script language="JavaScript" src="http://www.counter160.com/js.js?img=11"></script><br><a href="http://www.000webhost.com"><img src="http://www.counter160.com/images/11/left.png" alt="Free web hosting" border="0" align="texttop"></a><a href="http://www.hosting24.com"><img alt="Web hosting" src="http://www.counter160.com/images/11/right.png" border="0" align="texttop"></a>
    <!-- END OF HIT COUNTER CODE -->
    <!-- Google Code for music home impression Conversion Page -->
    <script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 1002796936;
    var google_conversion_language = "en";
    var google_conversion_format = "2";
    var google_conversion_color = "ffffff";
    var google_conversion_label = "9b6gCLj68QUQiO-V3gM";
    var google_conversion_value = 0;
    /* ]]> */
    </script>
    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
    </script>
    <noscript>
    <div style="display:inline;">
    <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1002796936/?value=0&amp;label=9b6gCLj68QUQiO-V3gM&amp;guid=ON&amp;script=0"/>
    </div>
    </noscript>
    </div>
  </div>
</body>	
</html>
					
				