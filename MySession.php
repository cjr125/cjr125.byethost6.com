<?php


/**
 * Description of MySession
 *
 * @author Joseluis Laso
 */
class MySession {
    
    private $filename;
    
    public function __construct($sid,$folder="/tmp") {        
        $this->filename = $folder.'/'.$sid.'.ses';
    }
    
    public function write($mixed){
        file_put_contents($this->filename, json_encode($mixed));
    }
    
    public function read(){
        if (file_exists($this->filename))
            return file_get_contents($this->filename);
        else
            return "";
    }
    
    public function writeKey($key,$mixed){
        $all = json_decode($this->read(),true);       
        $all[$key] = $mixed;
        $this->write($all);
    }
    
    public function readKey($key){
        $all = json_decode($this->read(),true);
        return isset($all[$key])?$all[$key]:null;
    }
}

/* Ejemplo de uso para ver el estado de una actividad
     <?php  // status.php
        if (isset($_REQUEST['sid'])) session_id($_REQUEST['sid']);
        session_start();

        // MySession
        require './lib/MySession.php';
        $mysession = new MySession(session_id());

        $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

        $p = $mysession->readKey('percent_'.$id);

        print(json_encode(array(
                            "p"  => $p,
                            "id" => $id,
             )));
    ?>
 
 */

/* Ejemplo de uso para empezar la actividad desde una ruta de slim
    $app = Slim::getInstance();
    
    $id   = $app->request()->params('id');
    
    $mysession = new MySession( session_id() );
    
    $mysession->writeKey('percent_'.$id, 0);
    
    // hay que liberar la sesion para que status.php pueda trabajar
    session_write_close();
 */

?>