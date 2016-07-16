<?php 
/**
 *################################################################################
 *#                           Stnc File Upload and Ajax v2.5
 *################################################################################
 *# Class Name :Stnc File Upload and Ajax v2.5
 *# Script-Version:     3.0
 *# File-Release-Date:  22/12/2009 21:34
 *# update Date : 12,01,2010
 *# Php Version  : PHP 4.3.0+
 *# Official web site and latest version:  selmantunc.com
 *#==============================================================================
 *# Authors: selman tunc (selmantunc@gmail.com)
 *# Copyright © 2010   -   selmantunc.com    All Rights Reserved.
 *#
 *################################################################################
 * | This program is free software; you can redistribute it and/or             |
 * | modify it under the terms of the GNU General var License              	   |
 * | as published by the Free Software Foundation; either version 2            |
 * | of the License, or (at your option) any later version.                    |
 * |                                                                           |
 * | This program is distributed in the hope that it will be useful,           |
 * | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
 * | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
 * | GNU General var License for more details.                            	   |
 * |                                                                           |
 * +---------------------------------------------------------------------------+
 */

 
 /**
 * STNC upload Class
 *
 * @version   3.0
 * @author    SeLman TunÇ <stncweb@gmail.com>
 * @license   http://opensource.org/licenses/gpl-license.php GNU var License
 * @copyright SeLman TunÇ
 * @package   upload
 * @subpackage external
 */
 



/**
 * @package   upload
 * @subpackage external
 */
 
//no error do not show error for error is not with the real picture
//$error_report_old = error_reporting(0);

class stnc_file_upload {

    /**
     * for image resizing...
     *
     * @access puplic
     * @var boolean
     */
    var $picture_edit = FALSE;
    
    /**
     * picture width
     *
     * @access puplic
     * @var integer
     */
    var $pic_width = 640;
    
    /**
     * picture height
     *
     * @access puplic
     * @var integer
     */
    var $pic_height = 480;
    
    /**
     * image preview
     *
     * @access puplic
     * @var boolean
     */
    var $thumb_picture = FALSE;
    
    /**
     * The image preview path 
     *
     * @access puplic
     * @var string
     */
    var $thumb_pic_dir = '';
    
    /**
     * appended to preview the future example creek.jpg  --> creek_thumb.jpg
     *it may be necessary for database storage
     *also do not need to store the database files for preview
     *For more information, see examples
     *
     * @access puplic
     * @var string
     */
    var $thumb_pic_extension = '_thumb';
    
    /**
     * thumb picture width
     *
     * @access puplic
     * @var integer
     */
    var $thumb_pic_width = 300;
    
    /**
    * thumb picture height
     *
     * @access puplic
     * @var integer
     */
    var $thumb_pic_height = 300;
    
    /**
     * added to the image before writing the future
     *
     * @access puplic
     * @var string
     */
    var $_prefix = 'stnc_';
    
    /**
     * will spell the end of the image attachment
     *
     * @access puplic
     * @var string
     */
    var $suffix_ = '_stnc';
    
    /**
     * the image file type
     *
     * Byte     = B
     * Kilobyte = KB
     * MegaByte = MB
     * GigaByte = GB // not support but only just . :) desteklenmez
     *
     * @access puplic
     * @var string
     */
    var $pic_size_type = 'MB';
    
    /**
     * image size
     *
     * @access puplic
     * @var string
     */
    var $picture_size = '1.00';
    
    /**
      * except for the file type of in pictures (example (exe,pdf))
     *
     * @access puplic
     * @var string
     */
    var $size_type_file = 'KB';
    
    /**
    * except the file size of images
     *
     * @access puplic
     * @var string
     */
    var $size_files = '1.00';
    
    /**
     * upload files
     *
     * @access puplic
     * @var array
     */
    var $files = array();
    
    /**
    * error information
     *
     * @access puplic
     * @var string
     */
    var $error = NULL;

    
    /**
     * upload files 
     *
     * @access puplic
     * @var string
     */
    var $upload_dir = NULL;
    
    /**
     * upload info
     *
     * @access puplic
     * @var boolean
     */
    var $uploaded = false;
    
    /**
     * uploaded files
     *
     * @access puplic
     * @var array
     */
    var $uploaded_files = array();
    
    /**
     * new filename
     *
     * @access puplic
     * @var string
     */
    var $new_file_name = NULL;
    
    /**
     * information
     *
     * @access puplic
     * @var string
     */
    var $info = NULL;

    
    /**
     
      *extension control, assigning a new name, image editing
     *
     * @access puplic
     * @return boolean uploaded
     */
     
    function upload() {
        if (!$this->error) {
           
            for ($i = 0; $i < count($this->files['tmp_name']); $i++) {
            
                
                $this->new_file_name = $this->file_name_control($this->files['name'][$i]);
                
                move_uploaded_file($this->files['tmp_name'][$i], $this->upload_dir.'/'.$this->new_file_name);
                if ($this->picture_edit) { 
				
                    if ($this->file_extension($this->files['name'][$i]) == 'jpg' || $this->file_extension($this->files['name'][$i]) == 'jpeg')
                        $this->image_edit_jpe_g($this->upload_dir.'/'.$this->new_file_name);
                    elseif ($this->file_extension($this->files['name'][$i]) == 'gif')
                        $this->image_edit_gif($this->upload_dir.'/'.$this->new_file_name);
                    elseif ($this->file_extension($this->files['name'][$i]) == 'png')
                        $this->image_edit_png($this->upload_dir.'/'.$this->new_file_name);
                }
                if ($this->thumb_picture) {
				
                    if ($this->file_extension($this->files['name'][$i]) == 'jpg' || $this->file_extension($this->files['name'][$i]) == 'jpeg')
                        $this->image_edit_thumb_jpe_g($this->upload_dir.'/'.$this->new_file_name);
                    elseif ($this->file_extension($this->files['name'][$i]) == 'gif')
                        $this->image_edit_thumb_gif($this->upload_dir.'/'.$this->new_file_name);
                    elseif ($this->file_extension($this->files['name'][$i]) == 'png')
                        $this->image_edit_thumb_png($this->upload_dir.'/'.$this->new_file_name);
                }
                
                $this->uploaded_files[] = $this->new_file_name;
                //$this->info .= '<li><strong>'.$this->files['name'][$i].' isimli dosya, <strong>'.$this->new_file_name.'</strong> ismiyle yüklendi<br />(~'.round($this->files['size'][$i] / 1024, 2).' kb). Dosya Tipi : '.$this->file_extension($this->files['name'][$i]).'</li>';
                $this->info .= '1:'.$this->files['name'][$i].' isimli dosya '.$this->new_file_name.' loaded with the name<br />';
            }
            return $this->uploaded = true;
        }
    }

    
    /**
     *bad_character_rewrite
     *incompatible and removes unnecessary characters
     *
     * @access puplic
     * @param  string  $text dosya isimleri
     * @return string $text_rewrite
     */
    function bad_character_rewrite($text) {
    
        $first = array("\\", "/", ":", ";", "~", "|", "(", ")", "\"", "#", "*", "$", "@", "%", "[", "]", "{", "}", "<", ">", "`", "'", ",", " ", "&#287;", "&#286;", "ü", "Ü", "&#351;", "&#350;", "&#305;", "&#304;", "ö", "Ö", "ç", "Ç");
        $last = array("_", "_", "_", "_", "_", "_", "", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "", "_", "_", "g", "G", "u", "U", "s", "S", "i", "I", "o", "O", "c", "C");
        $text_rewrite = str_replace($first, $last, $text);
        return $text_rewrite;
    }

    
    /**
     *file_extension
     *find the file extension
     *
     * @access puplic
     * @param  string $file_name dosya isimleri
     * @return string
     */
    function file_extension($file_name) {
        $file_extension = strtolower(substr(strrchr($file_name, '.'), 1));
        return $file_extension;
    }

    
    /**
     *looks to double check and write permissions to the directory.
     *
     * @access puplic
     * @param  string
     * @return string
     */
    function upload_dir($upload_dir) {
        // dizin var mi?
        if (!is_dir($upload_dir)) {
            $this->error .= '2:'.$upload_dir.' Could not find a directory named!</li>';
        }
  
        if (is_dir($path) && !is_writable($upload_dir)) {
            $this->error .= '3:'.$upload_dir.' does not have write permissions to the directory named!</li>';
        }
        /*thumb folder control*/
        if ($this->thumb_picture) {
            if (!is_dir($this->thumb_pic_dir)) {
                $this->error .= '4:'.$this->thumb_pic_dir.' path names found in the small picture!</li>';
            }
         
            if (is_dir($path) && !is_writable($this->thumb_pic_dir)) {
                $this->error .= '5:'.$this->thumb_pic_dir.'name does not have write permissions to the road in the small picture!</li>';
            }
        }
        
        $this->upload_dir = $upload_dir;
    }

    
    /**
    *clean bad characters, the file Do the same name checks, generate random numbers
     *
     * @access puplic
     * @param  array
     * @return string
     */
    function file_name_control($file_name) {
        //
        $file_name = $this->bad_character_rewrite($file_name);
        if (!file_exists($this->upload_dir.'/'.$file_name)) {  
            return $file_name;
        } else {
            $unique_name = rand(0001, 9999).'_'.rand(0001, 99999).'_'.$file_name;//isim için rastgele say&#305; uret
            
            $_prefix = $this->Prefix($unique_name, $this->_prefix);
            
            return $_suffix = $this->Suffix($_prefix, $this->suffix_); 
        }
    }
    
    /**
     *ex creek.jpg -> creek_ek.jpg
     *
     * @access puplic
     * @param  string $filename
     * @param  string $suffix
     * @return string
     */
    function Suffix($filename, $suffix) {
        $file_info = pathinfo($filename);
        $file_name = $file_info['filename'];
        $ext = '.'.$file_info['extension'];
        return $result = $file_name.$suffix.$ext;
        
    }
    
    /**
     *ex "creek.jpg -> ek_creek.jpg"
     *
     * @access puplic
     * @param  string $filename dosya ad&#305;
     * @param  string $prefix onune verilecek isim
     * @return string
     */
    function Prefix($filename, $prefix) {
        $file_info = pathinfo($filename);
        $file_name = $file_info['filename'];
        $ext = '.'.$file_info['extension'];
        return $result = $prefix.$file_name.$ext;
    }

    
    /**
     * file information
     *
     * @access puplic
     * @param  array
     */
    function files($files) {
        if ($files) {
            for ($i = 0; $i < count($files); $i++) {
                if ($files['name'][$i]) {
                    $this->files['tmp_name'][] = $files['tmp_name'][$i];
                    $this->files['name'][] = $files['name'][$i];
                    $this->files['type'][] = $files['type'][$i];
                    $this->files['size'][] = $files['size'][$i];
                    
                }
            }
        }
    }

    
    /**
     * To resize for jpg
     *
     * @access puplic
     * @param  string $source_target
     */
    function image_edit_jpe_g($source_target) {
        $width_ = $this->pic_width;
        $height_ = $this->pic_height;
        list($width_org, $height_org) = getimagesize($source_target);
        if ($width_org >= $width_ && $height_org >= $height_) {
            $picture = imagecreatetruecolor($width_, $height_);
            $source = imagecreatefromjpeg($source_target);
            imagecopyresampled($picture, $source, 0, 0, 0, 0, $width_, $height_, $width_org, $height_org);
        
            imagejpeg($picture, $source_target);
            
        }
    }
    
    /**
     * To resize for gif 
     *
     * @access puplic
     * @param   string
     */
    function image_edit_gif($source_target) {
        $width_ = $this->pic_width;
        $height_ = $this->pic_height;
        list($width_org, $height_org) = getimagesize($source_target);
        if ($width_org >= $width_ && $height_org >= $height_) {
            $picture = imagecreatetruecolor($width_, $height_);
            $source = imagecreatefromgif($source_target);
            imagecopyresampled($picture, $source, 0, 0, 0, 0, $width_, $height_, $width_org, $height_org);
            imagegif($picture, $source_target);

            
        }
    }
    
    /**
     *To resize for  png 
     *
     * @access puplic
     * @param   string
     */
    function image_edit_png($source_target) {
        $width_ = $this->pic_width;
        $height_ = $this->pic_height;
        list($width_org, $height_org) = getimagesize($source_target);
        if ($width_org >= $width_ && $height_org >= $height_) {
            $picture = imagecreatetruecolor($width_, $height_);
            $source = imagecreatefrompng($source_target);
            imagecopyresampled($picture, $source, 0, 0, 0, 0, $width_, $height_, $width_org, $height_org);
            imagepng($picture, $source_target);
        }
    }
    
    /**
     *To resize for jpg ,thumb
     *
     * @access puplic
     * @param   string
     */
    function image_edit_thumb_jpe_g($source_target) {
        $width_ = $this->pic_width;
        $height_ = $this->pic_height;
        list($width_org, $height_org) = getimagesize($source_target);
     	if ($width_org > $this->thumb_pic_width && $height_org >  $this->thumb_pic_height) {
        $suffix_name = $this->Suffix($source_target, $this->thumb_pic_extension);
        $thumb = imagecreatetruecolor($this->thumb_pic_width, $this->thumb_pic_height);
        $source2 = imagecreatefromjpeg($source_target);
        imagecopyresampled($thumb, $source2, 0, 0, 0, 0, $this->thumb_pic_width, $this->thumb_pic_height, $width_, $height_);
        imagejpeg($thumb, $this->thumb_pic_dir.'/'.$suffix_name);
   } else 
	 { $suffix_name = $this->Suffix($source_target, $this->thumb_pic_extension);
	copy($this->upload_dir.'/'.$this->new_file_name, $this->thumb_pic_dir.'/'.$suffix_name);}
	}
    /**
     *To resize for gif (thumb)
     *
     * @access puplic
     * @param   string
     */
    function image_edit_thumb_gif($source_target) {
        $width_ = $this->pic_width;
        $height_ = $this->pic_height;
        list($width_org, $height_org) = getimagesize($source_target);
     	if ($width_org > $this->thumb_pic_width && $height_org >  $this->thumb_pic_height) {
        $suffix_name = $this->Suffix($source_target, $this->thumb_pic_extension);
        $thumb = imagecreatetruecolor($this->thumb_pic_width, $this->thumb_pic_height);
        $source2 = imagecreatefromgif($source_target);
        imagecopyresampled($thumb, $source2, 0, 0, 0, 0, $this->thumb_pic_width, $this->thumb_pic_height, $width_, $height_);
        imagegif($thumb, $this->thumb_pic_dir.'/'.$suffix_name);
   } else 
	{  $suffix_name = $this->Suffix($source_target, $this->thumb_pic_extension);
	copy($this->upload_dir.'/'.$this->new_file_name, $this->thumb_pic_dir.'/'.$suffix_name);}
	}
    
    /**
     *To resize for png (thumb)
     *
     * @access puplic
     * @param   string
     */
    function image_edit_thumb_png($source_target) {
        $width_ = $this->pic_width;
        $height_ = $this->pic_height;
        list($width_org, $height_org) = getimagesize($source_target);
     	if ($width_org > $this->thumb_pic_width && $height_org >  $this->thumb_pic_height) {
        $suffix_name = $this->Suffix($source_target, $this->thumb_pic_extension);
        $thumb = imagecreatetruecolor($this->thumb_pic_width, $this->thumb_pic_height);
        $source2 = imagecreatefrompng($source_target);
        imagecopyresampled($thumb, $source2, 0, 0, 0, 0, $this->thumb_pic_width, $this->thumb_pic_height, $width_, $height_);
        imagepng($thumb, $this->thumb_pic_dir.'/'.$suffix_name);
   } else 
	 { $suffix_name = $this->Suffix($source_target, $this->thumb_pic_extension);
	copy($this->upload_dir.'/'.$this->new_file_name, $this->thumb_pic_dir.'/'.$suffix_name);}
	}

    
    /**
     *convert all values to the genus kb
     *
     * @access puplic
     * @param   string  $size_type_file 
     * @param  integer value
     * @return	integer
     */
    function all2kbytes($value, $size_type_file) {
    
        switch ($size_type_file) {
            case 'B':
                $values = $value;
                break;
            case 'KB':
                $values = $value * 1024;
                break;
            case 'MB':
                $values = $value * 1024 * 1024;
                break;
            /*case 'GB':
             $values=$value*1024*1024*1024;
             */
        }
        //return $values = round($value);//byte
        $values = round($values / 1024); //kb
        return $values = round(($values * 1024), 2);//reapat byte
        // return $values=round($values / 1024 / 1024);//mb
        // return  $values=$values / 1024 / 1024 / 1024;//gb
        
    }
    /**
     *compares the size
     *
     * @access puplic
     * @param   string  $file 
     * @param  integer $size 
     * @param  integer $file_size 
     * @return	integer
     */
    function size_compare($size, $file_size, $file) {
        if ($size > $file_size) {
            $this->error .= '6:'.$file.' sizes too big';
            
        }
    }
    
    /**
     *checking size of
     *
     * @access puplic
     * @return	boolean
     */
    function size_find() {
        if (!$this->error) {
            $mime_types_picture = array('image/pjpeg', 'image/jpeg', 'image/gif', 'image/png', 'image/x-png');
            
            for ($i = 0; $i < count($this->files['tmp_name']); $i++) {
                if (in_array($this->files['type'][$i], $mime_types_picture)) {
                
                    $file_size_pic = $this->all2kbytes($this->picture_size, $this->pic_size_type);
                    
                    $this->size_compare($this->files['size'][$i], $file_size_pic, $this->files['name'][$i]);
                    
                } else {
                    $file_size = $this->all2kbytes($this->size_files, $this->size_type_file);
                    $this->size_compare($this->files['size'][$i], $file_size, $this->files['name'][$i]);
                }
            }
        }
    }
    
    /**	 
     * gd check my add-in installed
     * checks whether a real picture
     *
     *
     * @access  puplic
     */
    function image_control() {
        $mime_types_picture = array('image/pjpeg', 'image/jpeg', 'image/gif', 'image/png', 'image/x-png');
        
        for ($i = 0; $i < count($this->files['tmp_name']); $i++) {
            if (in_array($this->files['type'][$i], $mime_types_picture)) {

            
                if (extension_loaded('gd') && !imagecreatefromstring(file_get_contents($this->files['tmp_name'][$i])))
                
                    $this->error .= '7:'.$this->files['name'][$i].' not a true picture file ';
                    
                elseif (!getimagesize($this->files['tmp_name'][$i]))
                    $this->error .= '7:'.$this->files['name'][$i].' not a true picture file';
                    
            }
        }
    }

    
     /**	 
     * of a file, check if the extension would be compatible
     *
     * @access  puplic
     * @param  array
     */
    function is_file_extension($mime_types) {
    
     
        for ($i = 0; $i < count($this->files['tmp_name']); $i++) {
       
            if (!in_array($this->file_extension($this->files['name'][$i]), $mime_types))
                //echo "tür hata ".$this->files['type'][$i];
             
                $this->error .= $this->files['name'][$i].' <li> named file was not installed because of incompatible file type!</li>';
        }
    }

    
    /**
     *reports
     *
     * @access puplic
     */
    function result_report() {
        if (isset($this->error)) {
            //echo '<ul>';
            echo $this->error;
            //echo '</ul>';
        }
        if ($this->uploaded == true) {
            // echo '<ul>';
            echo $this->info;
            // echo '</ul>';
        }
    }

    /**
     * first define the values of
     * @param string $_prefix
     * @param string $suffix_
     * @param string $size_type_file
     * @param string $size_files
     */
    function first_values($_prefix, $suffix_, $size_type_file, $size_files) {
        $this->suffix_=$suffix_;
        $this->_prefix=$_prefix;
        $this->size_type_file = $size_type_file;
        $this->size_files = $size_files;
    }

    /**
     * image editing function
     * 
     * @param boolean $picture_edit
     * @param integer $pic_width
     * @param integer $pic_height
     * @param string $pic_size_type
     * @param string $picture_size
     */
    function picture_edit_values ($picture_edit, $pic_width, $pic_height, $pic_size_type, $picture_size) {
        $this->picture_edit = $picture_edit;
        $this->pic_width = $pic_width;
        $this->pic_height = $pic_height;
        $this->pic_size_type = $pic_size_type;
        $this->picture_size = $picture_size;
    }
    
	/**
	 * image editing function for thumb 
	 * 
	 * @param boolean $thumb_picture
	 * @param string $thumb_pic_dir
	 * @param integer $pic_width
	 * @param integer $pic_height
	 * @param string $thumb_pic_extension
	 */
    function picture_edit_thumb_values($thumb_picture, $thumb_pic_dir, $thumb_pic_width, $thumb_pic_height,$thumb_pic_extension) {
        $this->thumb_picture = $thumb_picture;
        $this->thumb_pic_dir = $thumb_pic_dir;
       $this->thumb_pic_width = $thumb_pic_width;
	    $this->thumb_pic_height = $thumb_pic_height;
        $this->thumb_pic_extension =$thumb_pic_extension;
    }

    
    /**
     *founder of the function, all the work starts here
     *
     * @access puplic
     * @param   array  $files
     * @param  string $upload_dir 
     * @param  array $mime_types 
     */
    function uploader_set($files, $upload_dir, $mime_types) {
    
        $this->upload_dir($upload_dir);
        $this->files($files);
        $this->is_file_extension($mime_types);
        $this->size_find();
        $this->image_control();
        $this->upload();
        
    }
}
error_reporting($error_report_old);

?>
