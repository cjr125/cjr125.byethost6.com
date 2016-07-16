<?
/*
    This is an Open Source Audio-File Creation class written in PHP
    Feel free to send me a comment. I'm new to making public classes.
    
     Author: Thomas Schmidt, http://renaicompix.net
             me@renaicompix.net
    Version: 0.6.2-INDEV
*/
class wavcreation {
 private $samplerate = false;
 private $samplesize = false;
 private $channelnum = false;
 private $currentarray = array("0" => array(), "1" => array());
 private $maxamplitude = 1;
 private $highsample = false;
 
 public function generate($filename) {
  $this->fill_samplearray();
  $pointer = fopen($filename, "w+");
  if($pointer) {
   fwrite($pointer, "RIFF");
   
   
   $data = "";
   if($this->channelnum == 2) {
    for($i = 0; $i < count($this->currentarray[0])-1; ++$i) {
     $data .= $this->hex2ascii($this->signed2hex(round((int) ($this->currentarray[0][$i]*$this->maxamplitude), 0), $this->samplesize));
     $data .= $this->hex2ascii($this->signed2hex(round((int) ($this->currentarray[1][$i]*$this->maxamplitude), 0), $this->samplesize));
    }
   }
   else {
    for($i = 0; $i < count($this->currentarray[0])-1; ++$i) {
     $data .= $this->hex2ascii($this->signed2hex(round((int) ($this->currentarray[0][$i]*$this->maxamplitude), 0), $this->samplesize));
    }
   }
   
   fwrite($pointer, $this->hex2ascii($this->signed2hex(36+strlen($data), 32)));
   fwrite($pointer, "WAVEfmt ");
   fwrite($pointer, $this->hex2ascii($this->signed2hex(16, 32)));
   fwrite($pointer, $this->hex2ascii($this->signed2hex(1, 16)));
   fwrite($pointer, $this->hex2ascii($this->signed2hex($this->channelnum, 16)));
   fwrite($pointer, $this->hex2ascii($this->signed2hex($this->samplerate, 32)));
   fwrite($pointer, $this->hex2ascii($this->signed2hex($this->channelnum*$this->channelnum*$this->getSamplebytes($this->samplesize), 32)));
   fwrite($pointer, $this->hex2ascii($this->signed2hex($this->channelnum*$this->getSamplebytes($this->samplesize), 16)));
   fwrite($pointer, $this->hex2ascii($this->signed2hex($this->samplesize, 16)));
   fwrite($pointer, "data");
   fwrite($pointer, $this->hex2ascii($this->signed2hex(strlen($data), 32)));
   fwrite($pointer, $data);
   fclose($pointer);
  }
  
 }
 
 private function fill_samplearray() {
  if($this->channelnum == 2) {
   for($i = 0; $i < $this->highsample; ++$i) {
    if(!array_key_exists($i, $this->currentarray[0])) {
     $this->currentarray[0][$i] = 0;
    }
    if(!array_key_exists($i, $this->currentarray[1])) {
     $this->currentarray[1][$i] = 0;
    }
   }
  }
  else {
   for($i = 0; $i < $this->highsample; ++$i) {
    if(!array_key_exists($i, $this->currentarray[0])) {
     $this->currentarray[0][$i] = 0;
    }
   }
  }
 }
 
 public function addfile($filename, $settings) {
  $newarray = $this->parseWave($filename, $settings);
  $ssize = $this->getSamplebytes($this->samplesize);
  $volume = pow(2, $this->samplesize-8);
  if($newarray !== false) {
   $ni = 0;
   for($i = $settings['starttime_output']; $i <= $settings['starttime_output']+count($newarray[0]); ++$i) {
   
    if($i > $this->highsample) {
     $this->highsample = $i;
    }
   
    if($this->channelnum == 2) {
     if(count($newarray[1]) > 0) {
      $this->currentarray[0][$i] = $this->currentarray[0][$i]+$newarray[0][$ni]*$volume;
      $this->currentarray[1][$i] = $this->currentarray[1][$i]+$newarray[1][$ni]*$volume;
     }
     else {
      $this->currentarray[0][$i] = $this->currentarray[0][$i]+$newarray[0][$ni]*$volume;
      $this->currentarray[1][$i] = $this->currentarray[0][$i]+$newarray[0][$ni]*$volume;
     }
    }
    else {
     $this->currentarray[0][$i] = $this->currentarray[0][$i]+$newarray[0][$ni]*$volume;
    }
    $ni++;
   }
   unset($newarray);
  }
  else {
   return false;
  }
 }
 
 private function parseWave($filename, $settings) {
  $fp = fopen($filename, "r");
  if($fp) {
   $filearray = array();
   $format = fread($fp, 4);
   if($format == "RIFF") {
    $restsize = hexdec($this->ascii2hex(fread($fp, 4)));
    $waveformat = fread($fp, 8);
    if($waveformat == "WAVEfmt ") {
     $dontcare = fread($fp, 6); // I don't care about those caracters in this version of the script
     $channelnum = hexdec($this->ascii2hex(fread($fp, 2)));
     $sampleratefile = hexdec($this->ascii2hex(fread($fp, 4)));
     $byterate = hexdec($this->ascii2hex(fread($fp, 4)));
     $blockalign = hexdec($this->ascii2hex(fread($fp, 2)));
     $samplebits = hexdec($this->ascii2hex(fread($fp, 2)));
     $samplebytes = $this->getSamplebytes($samplebits);
     $dontcare = fread($fp, 8); // I know this will be "data" and some number not needed for basic function (:
     $retarray = array(0 => array(), 1 => array());
     if(!empty($settings['starttime_input'])) {
      $dontcare = fread($fp, ($settings['starttime_input']*$channelnum*$samplebytes));
     }
     if($byterate == ($sampleratefile*$channelnum*$samplebytes)) {
      $length = 0;
      $regularvolume = pow(2, 8-$samplebits);
      while(!feof($fp)) {
       if(!empty($settings['endtime_input'])) {
        if($length < $settings['endtime_input']) {
         // With Limit
         if($channelnum == 1) {
          $value = $this->hex2signed($this->ascii2hex(fread($fp, $samplebytes)), $samplebits);
          $retarray[0][] = $value*$regularvolume;
         }
         else {
          $valueleft = $this->hex2signed($this->ascii2hex(fread($fp, $samplebytes)), $samplebits);
          $valueright = $this->hex2signed($this->ascii2hex(fread($fp, $samplebytes)), $samplebits);
          $retarray[0][] = $valueleft*$regularvolume;
          $retarray[1][] = $valueright*$regularvolume;
         }
        }
       }
       else {
        if($channelnum == 1) {
         $value = $this->hex2signed($this->ascii2hex(fread($fp, $samplebytes)), $samplebits);
         $retarray[0][] = $value*$regularvolume;
        }
        else {
         $valueleft = $this->hex2signed($this->ascii2hex(fread($fp, $samplebytes)), $samplebits);
         $valueright = $this->hex2signed($this->ascii2hex(fread($fp, $samplebytes)), $samplebits);
         $retarray[0][] = $valueleft*$regularvolume;
         $retarray[1][] = $valueright*$regularvolume;
        }
       }
       $length++;
      }
      return $retarray;
      fclose($fp);
      break;
     }
    }
   }
   fclose($fp);
  }
   return false;
 }
 
 private function getSamplebytes($bits) {
  if($bits % 8 != 0) {
   $retu = (($bits-($bits%8))/8)+1;
  }
  else {
   $retu = $bits/8;
  }
  return $retu;
 }
 
 public function timetosample($timefloat) {
  if($this->samplerate !== false) {
   $samplestart = round($this->samplerate*$timefloat, 0);
   return $samplestart;
  }
  else {
   return false;
  }
 }
 
 public function setSamplerate($samplerate) {
  $allowed_rates = array(8000, 16000, 22050, 44100, 48000); // allowed framerates
  if(in_array($samplerate, $allowed_rates)) {
   $this->samplerate = $samplerate;
   return true;
  }
  else {
   return false;
  }
 }
 
 public function setSamplesize($samplesize) {
  $this->samplesize = $samplesize;
  return true;
 }
 
 public function setAmplitude($ampl) {
  $this->maxamplitude = $ampl;
  return true;
 }
 
 public function setChannels($channelnum) {
  $allowed_channels = array(1, 2);
  if(in_array($channelnum, $allowed_channels)) {
   $this->channelnum = $channelnum;
   return true;
  }
  else {
   return false;
  }
 }
 
 private function hexbin ($hex) {
  $map = array("0" => "0000", "1" => "0001", "2" => "0010", "3" => "0011", "4" => "0100", "5" => "0101", "6" => "0110", "7" => "0111",  "8" => "1000", "9" => "1001", "A" => "1010", "B" => "1011", "C" => "1100", "D" => "1101", "E" => "1110", "F" => "1111");
  $bin = "";
  for($i = strlen($hex); $i > 0; --$i) $bin = $map[$hex{$i-1}].$bin;
  return $bin;
 }
 
 private function hex2signed ($hex, $bits) {
  $hex = $this->check_hex($hex, $bits);
  $binary = $this->hexbin($hex);
  if($binary{0} == 1) {
   for($i = strlen($binary); $i > 0; --$i) {
    if($binary{$i-1} == 1) {
     $binary{$i-1} = 0;
    }
    else {
     $binary{$i-1} = 1;
    }
   }
   $result = -bindec($binary)-1;
  }
  else {
   $result = bindec($binary);
  }
  return $result;
 }
 
 private function signed2hex ($dec, $bits) {
  if($dec < 0) {
   $dec = $dec*(-1)-1;
   $bin = decbin($dec);
   $bin = str_repeat("0", ($bits-1)-strlen($bin)).$bin;
   for($i = 0; $i < strlen($bin); ++$i) {
    if($bin{$i} == "1") {
     $bin{$i} = "0";
    }
    else {
     $bin{$i} = "1";
    }
   }
   $bin = "1".$bin;
  }
  elseif($dec > 0) {
   $bin = decbin($dec);
  }
  else {
   $bin = "0";
  }
  
  $hex = dechex(bindec($bin));
  
  $len = strlen($hex);
  if($bits%8 == 0) {
   $needed = $bits/4;
  }
  else {
   $needed = (($bits-($bits%8))/8+1)*2;
  }
  if($len != $needed) {
   $hex = str_repeat("0", $needed-$len).$hex;
  }
   return $hex;
 }
 
 
 private function check_hex($hex, $bits) {
  if(($bits % 4) != 0) {
   return false;
  }
  else {
   $len = strlen($hex);
   if($len*4 == $bits) {
    return $hex;
   }
   elseif($len*4 < $bits) {
    $hex = str_repeat("0", (($bits/4)-$len)).$hex;
    return $hex;
   }
   else {
    return false;
   }
  }
 }
 
 /*
 The following two functions are copied and changed a bit from http://bytes.com/topic/php/answers/519762-ascii-hex
 Maybe I will replace them soon with my way of doing this.. don't know now...
 */
 private function ascii2hex($ascii) {
  $ascii = strrev($ascii);
  $hex = "";
  for ($i = 0; $i < strlen($ascii); $i++) {
   $byte = strtoupper(dechex(ord($ascii{$i})));
   $byte = str_repeat('0', 2-strlen($byte)).$byte;
   $hex.= $byte;
  }
  return $hex;
 }
 
 private function hex2ascii($hex){
  $ascii="";
  for($i=0; $i<strlen($hex); $i=$i+2) {
   $ascii.=chr(hexdec(substr($hex, $i, 2)));
  }
  $ascii = strrev($ascii);
  return($ascii);
  }
}
?>