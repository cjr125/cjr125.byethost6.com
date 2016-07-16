<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Demo :: Rating Stars System With Php jQuery & Ajax</title>

        <link type="text/css" rel="stylesheet" href="css/style.css">
        <link type="text/css" rel="stylesheet" href="css/example.css">
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
           
    </head>
 
    <body>
    <?php  
        require_once 'config.php';
        $post_id = '1'; 
    ?>

        
     <div class="webcodo-top" >
        <a href="http://www.webcodo.net/rating-stars-system-with-jquery-ajax-php">
            <div class="wcd wcd-tuto"> < Come back to the tuto page</div>
        </a>
        <a href="http://webcodo.com">
            <div class="wcd wcd-logo">WEBCODO</div>
        </a>
        <div class="wcd"></div>
    </div>

    
    <div class="tuto-cnt">

        <div class="rate-ex1-cnt">
            <div id="1" class="rate-btn-1 rate-btn"></div>
            <div id="2" class="rate-btn-2 rate-btn"></div>
            <div id="3" class="rate-btn-3 rate-btn"></div>
            <div id="4" class="rate-btn-4 rate-btn"></div>
            <div id="5" class="rate-btn-5 rate-btn"></div>
        </div>

        <hr>
        
        <div class="rate-ex2-cnt">
            <div id="1" class="rate-btn-1 rate-btn"></div>
            <div id="2" class="rate-btn-2 rate-btn"></div>
            <div id="3" class="rate-btn-3 rate-btn"></div>
            <div id="4" class="rate-btn-4 rate-btn"></div>
            <div id="5" class="rate-btn-5 rate-btn"></div>
        </div>

        <hr>

        <div class="rate-ex3-cnt">
            <div id="1" class="rate-btn-1 rate-btn"></div>
            <div id="2" class="rate-btn-2 rate-btn"></div>
            <div id="3" class="rate-btn-3 rate-btn"></div>
            <div id="4" class="rate-btn-4 rate-btn"></div>
            <div id="5" class="rate-btn-5 rate-btn"></div>
        </div>


        <br><br><br><br><br>

        <div class="box-result-cnt">
            <?php
                $query = mysql_query("SELECT * FROM wcd_rate"); 
                while($data = mysql_fetch_assoc($query)){
                    $rate_db[] = $data;
                    $sum_rates[] = $data['rate'];
                }
                if(@count($rate_db)){
                    $rate_times = count($rate_db);
                    $sum_rates = array_sum($sum_rates);
                    $rate_value = $sum_rates/$rate_times;
                    $rate_bg = (($rate_value)/5)*100;
                }else{
                    $rate_times = 0;
                    $rate_value = 0;
                    $rate_bg = 0;
                }
            ?>
            <hr>
            <h3>The content was rated <strong><?php echo $rate_times; ?></strong> times.</h3>
            <hr>
            <h3>The rating is at <strong><?php echo $rate_value; ?></strong> .</h3>
            <hr>
            <div class="rate-result-cnt">
                <div class="rate-bg" style="width:<?php echo $rate_bg; ?>%"></div>
                <div class="rate-stars"></div>
            </div>
            <hr>

        </div><!-- /rate-result-cnt -->

    </div><!-- /tuto-cnt -->


    
    <script>
        // rating script
        $(function(){ 
            $('.rate-btn').hover(function(){
                $('.rate-btn').removeClass('rate-btn-hover');
                var therate = $(this).attr('id');
                for (var i = therate; i >= 0; i--) {
                    $('.rate-btn-'+i).addClass('rate-btn-hover');
                };
            });
                            
            $('.rate-btn').click(function(){    
                var therate = $(this).attr('id');
                var dataRate = 'act=rate&post_id=<?php echo $post_id; ?>&rate='+therate; //
                $('.rate-btn').removeClass('rate-btn-active');
                for (var i = therate; i >= 0; i--) {
                    $('.rate-btn-'+i).addClass('rate-btn-active');
                };
                $.ajax({
                    type : "POST",
                    url : "http://localhost/rating/ajax.php",
                    data: dataRate,
                    success:function(){}
                });
                
            });
        });
    </script>


</body>
</html>