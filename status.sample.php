<?php
    if (isset($_REQUEST['sid'])) session_id($_REQUEST['sid']);
    session_start();
    
    // MySession
    require '../MySession/MySession.php';
    $mysession = new MySession(session_id());
    
    $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
    
   
    // this sends to requester the percent of activiy and id,
    // normally the requester is a javascript routine and for not
    // use global id vars now this script returns id
    print(json_encode(array(
                        "p"  => $mysession->readKey($id),
                        "id" => $id,
         )));
    
        
?>