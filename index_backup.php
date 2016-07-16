<!DOCTYPE html>
<html>
<head>
  <?php require 'db.php';
    $referer = $_SERVER["HTTP_REFERER"];
    if ($referer != "") {
      DBLogin("a8823305_audio");
      $result = DBQuery("INSERT INTO referers (url, uri, remote_addr, useragent) VALUES('".$referer."', '".$_SERVER["REQUEST_URI"]."', '".$_SERVER["REMOTE_ADDR"]."', '".$_SERVER["HTTP_USER_AGENT"]."')");
      DBC();
    }
  ?>

  <title>Music</title>
  <meta name="description" content="Harmonies and associated rhythms - The limit of one process: expense."> 
<meta name="keywords" content="listen to free music,music site,free music online,free online music,jazz online,mp3 music,music mp3,reggae,free music,online music,jazz,free mp3,rock,hip hop,rock music,rap,hiphop,jazz music,rock music online,music songs,free jazz,music players,mp3 free,listen to music,streaming music,music online,free mp3 songs,free music websites,free mp3 music files,free music player,free music players,free music to listen to,online music sites,listen music online,listen to online music,online free music,listening to music online,listen online music,online music free,hear music online,music online free,online music listen,listen to free music online,music listen,music website,listen to music free,music websites,play music online,jazz music online,listen online,play music,free song,free beats,free music mp3,free songs,music streaming,mp3 sites,listen to music online,free music sites,free mp3 sites,music sites,free music site,free mp3 music,music mp3 free,mp3 free music,mp3 music free,listen music,hear music,music jazz,music stream,music to listen to,music rock,music for free,music free,stream music,free music website,music streaming sites,listen to rock music,free rock music,listen to songs,listen to music for free,free new music,songs online,cool jazz,free play music,play free music,listening to music,playing music,online streaming music,play songs online,listen to songs online,online songs,listening music,listen music free,listen to free online music,free music for mp3,free listen music,free music to listen,music to listen,free streaming music,free music downloading sites,free music listening,listen free music,free mp3 music sites,free music listen,music play,music listening,mp3 songs free,online music listening,music to listen to online,listen to song,free jazz music,get free music,rock online,song free,sites to listen to music,music listen online,website to listen to music,free listen to music,listen to music free online,websites to listen to music,music online listen,music free listen,music online for free,music free online,songs free,free instrumental music,free listening music,listen to music now,listen to music online free,music listening sites,free music songs,free audio music,songs for free,streaming music online,songs to listen to,free songs online,listen songs online,free online songs,listen music online free,music online streaming,streaming music free,listening music online,streaming music sites,online music streaming,listen to free music now,music listen free,listen to songs for free,listen to free songs,online listen music,dubstep,free songs to listen to,online listening music,play online music,free online music listening,listen free online music,listen to music songs,free music streaming,listen music for free,play music for free,listen free music online,free music stream,free jazz music online,free music play,free music listen now,hear music for free,free music to play,free music listen online,play free music online,free music for phone,free music listening sites,hear free music,hear free music online,music listening online,free online music sites,stream music online,music listening websites,free full songs,free music playlist,free music online streaming,music sites for free,play music free online,free listen to music online,play music online free,listening to free music online,free music mp3 songs,listening to free music,music playing websites,music online free listening,free online music streaming,free online music songs,listen music free online,free online music listen,music websites for free,music for free online,online free music listen,listen online music free,free music streaming websites">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <link rel="stylesheet" type="text/css" href="css/common.css" />
  <style type="text/css">
	#overlay {
	    position: fixed;
	    top: 0;
	    left: 0;
	    width: 100%;
	    height: 100%;
	    background-color: #000;
	    filter:alpha(opacity=70);
	    -moz-opacity:0.7;
	    -khtml-opacity: 0.7;
	    opacity: 0.7;
	    z-index: 100;
	    display: none;
	}
	.content a{
	    text-decoration: none;
	}
	.welcome{
	    width: 750px;
	    margin: 0 auto;
	    display: none;
	    position: fixed;
	    z-index: 101;
	}
	.content{
	    min-width: 600px;
	    width: 600px;
	    min-height: 150px;
	    margin: auto;
	    background: #f3f3f3;
	    position: relative;
	    z-index: 103;
	    padding: 10px;
	    border-radius: 5px;
	    box-shadow: 0 2px 5px #000;
	}
	.content p{
	    clear: both;
	    color: #555555;
	    text-align: justify;
	}
	.content p a{
	    color: #d91900;
	    font-weight: bold;
	}
	.content .x{
	    float: right;
	    height: 35px;
	    left: 22px;
	    position: relative;
	    top: -25px;
	    width: 34px;
	}
	.content .x:hover{
	    cursor: pointer;
	}
        #pager {
          width:100%;
          padding: 0 28%;
          clear:both;
        }
        #pager, #pager li {
          float:left;
        }
        .page {
           width:20px;
        }
  </style>
  <script type="text/javascript" src="js/jquery-1.8.1.js"></script>
  <script type="text/javascript" src="scripts/contextmenu.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $(window).scroll(function() {
        if ($(window).scrollTop() > 390) {
          if (($('.welcome')[0].clientHeight - $(window).scrollTop()) > 0) {
            $('.welcome').animate({"marginTop": ($(window).scrollTop() + 400) + "px"}, 5000);
          }
        } else {
          $('.welcome').animate({"marginTop": "0px"}, 5000);
        }
      });
    });
    $(function(){
      var overlay = $('<div id=overlay></div>');
      $('.close').click(function(){
      $('.welcome').hide();
      overlay.appendTo(document.body).remove();
      return false;
    });
    $('.x').click(function(){
      $('.welcome').hide();
      overlay.appendTo(document.body).remove();
      return false;
    });
    $('.click').click(function(){
      overlay.show();
      overlay.appendTo(document.body);
      $('.welcome').show();
      return false;
    });
  });
</script>
</head>
<body>
  <div class="container">
    <h1>Music</h1>
    <div class="overlay"></div>
    <div class='welcome'>
      <div class='content' id='content'>
        <img src='http://www.cjr125.netai.net/images/close.png' alt='quit' class='x' id='x' />
	<p>
	Thank you for visiting my music site. Welcome to the first step in this project to help people share their creativity with the world online! 
	With the associated media and any computer system with audio recording capabilities, it is possible to collaborate musically from any mobile device with internet access.
	<br/><br/>
	<a href='' class='close'>Close</a>
	</p>
      </div>
    </div>         
    <div id='container'>
      <a href='' class='click'><h2><b>Click here to see how you can get involved!</b></h2></a> <br/>
    </div>
    <ul id="#spotId">
      <?php
        $pageSize = 20;
        $page = $_REQUEST["page"] == null ? 1 : $_REQUEST["page"];
        $currentPageTracks = 0;
        $totalTracks;
        $folder = opendir("audio/");
        while (($file = readdir($folder)) !== false) {
          if ($file != "." && $file != "..") {
            $name = substr($file, 0, strpos($file, "."));
            if ($name != "") {
              if ($totalTracks <= $pageSize * $page && $totalTracks > ($pageSize * ($page - 1))) {
                echo '<li><span class="trackName">'.$name.'</span><audio controls="controls"><source src="/audio/'.$file.'" type="audio/mpeg" /></audio></li>';
                if ($currentPageTracks < $pageSize) {
                  $currentPageTracks++;
                } else {
                  $page++;
                  $currentPageTracks = 0;
                }
              }
              $totalTracks++;
            }
          }
        }
        $totalTracks--;
        closedir($folder);
      ?>
    </ul>
    <?php 
      $page = $_REQUEST["page"] == null ? 1 : $_REQUEST["page"];
      echo '<div>Tracks: '.($pageSize * ($page - 1) + 1).' - '.($totalTracks <= $pageSize * $page ? $totalTracks : $pageSize * $page).' of '.$totalTracks.'</div>'; 
    ?>
    <ul id="pager">
      <?php
        $pages = $totalTracks / $pageSize + 1;
        for ($i = 1; $i < $pages; $i++) {
          echo '<li><a href="http://www.cjr125.netai.net?page='.$i.'"><span class="page">'.$i.'</span></a></li>';
        }
      ?>
    </ul>
    <a href="http://www.linkedin.com/in/chrisroberts7">
      <img src="http://www.cjr125.netai.net/images/default.jpg" width="160" height="25" border="0" alt="View Chris Roberts's profile on LinkedIn"> 
    </a>
<br>
<a href="http://www.000webhost.com/" target="_blank"><img src="http://www.000webhost.com/images/80x15_powered.gif" alt="Web Hosting" width="80" height="15" border="0" /></a>
    <!--<script type="text/javascript" src="js/ad.js"></script>-->
    <div style="display:none;">
    <!-- START OF HIT COUNTER CODE -->
    <br><script language="JavaScript" src="http://www.counter160.com/js.js?img=11"></script><br><a href="http://www.000webhost.com"><img src="http://www.counter160.com/images/11/left.png" alt="free web hosting" border="0" align="texttop"></a><a href="http://www.hosting24.com"><img alt="Hosting24.com web hosting" src="http://www.counter160.com/images/11/right.png" border="0" align="texttop"></a>
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
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-26417016-1', 'example.com');
ga('require', 'displayfeatures');
ga('send', 'pageview');
</script>
    </div>
  </div>
</body>	
</html>
												

