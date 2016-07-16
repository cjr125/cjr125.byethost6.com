<?php    if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/**
 * CodeIgniter Audio_play
 *
 * Returns strings containing the HTML needed for embedding audio player(s).
 * This is based on Word Press' famous audio player, which can be found at:
 * http://wpaudioplayer.com/download/
 * Use the stand-alone version.
 *
 * The class is intended for CodeIgniter framework, but by changing the top line
 * from:
 * <?php    if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 * to:
 * <?php
 * and changing
 * from:
 * class Audio_play {
 * to:
 * class audio_play {
 * the class should be okay for a non-framework solution.
 *
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Carl Friis-Hansen (carl dot friis-hansen at carl-fh dot com)
 * @license        GNU General Public License (GPL)
 * @date        2012-06-28
 * @link
 */

 

class Audio_play {


    protected
        $header_player_js,
        $header_player_swf,
        $options = array(),
        $instance;



    //
    //    Use constructor like this:
    //        $pl = new audio_play ('audio-player.js', 'player.swf');
    //
    //    $player_js        Where is the 'audio-player.js' file?
    //    $player_swf        Where is the 'player.swf' file?
    //
    public    function    __construct (    $player_js        = "audio-player.js",
                                        $player_swf        = "player.swf"
                                        )
    {
        $this->header_player_js        = '<script type="text/javascript" src="' . $player_js . '"></script>';
        $this->header_player_swf    = '<script type="text/javascript">' .
                                        'AudioPlayer.setup("' . $player_swf . '", {' .
                                        'width: 290' .
                                        '});' .
                                        '</script>';
        $this->instance                = 0;
    }



    //
    //    Use this in the header like:
    //        $data['incl_in_header'] .= $pl->get_header_player_js ();
    //
    public    function    get_header_player_js ()
    {
        return    $this->header_player_js;
    }



    //
    //    Use this in the header like:
    //        $data['incl_in_header'] .= $pl->get_header_player_swf ();
    //
    public    function    get_header_player_swf ()
    {
        return    $this->header_player_swf;
    }



    //
    //    Use this in the body like:
    //        $data2['audioplayer_1'] = $pl->play ('/Ramona Fottner - Run.mp3');
    //
    public    function    play ($file)
    {
        $this->instance++;
        $ret = "\n<!-- Audio player insert ---- begin ---- -->\n";
        $ret .= '<p id="audioplayer_' . $this->instance . '">' . $file . '</p>' . "\n";
        $ret .=    '<script type="text/javascript">';
        $ret .=    'AudioPlayer.embed("audioplayer_' . $this->instance . '", {';
        $ret .=    'soundFile: "' . $file . '"';
        foreach ($this->options as $key => $value)
        {
            $ret .= ', ' . $key . ': "' . $value . '"';
        }
        $ret .=    '});' . "\n";
        $ret .=    '</script>';
        $ret .=    "\n<!-- Audio player insert ---- end ---- -->\n";
        return    $ret;
    }


    //
    //    Use all the options like this:
    //
    //        $pl->set_option_width (350);
    //        $pl->set_option_animation ('no');
    //


    //
    //    Width is normally set in setup to 290 pixels
    //
    public    function    set_option_width ($width)
    {
        $this->options['width'] = $width;
    }



    //
    //    Default 'no'
    //
    public    function    set_option_autostart ($autostart)
    {
        $this->options['autostart'] = $autostart;
    }



    //
    //    Overwrites ID3 information (single string or csv)
    //    If not set, the ID3 info is used
    //
    public    function    set_option_titles ($titles)
    {
        $this->options['titles'] = $titles;
    }



    //
    //    Overwrites ID3 information (single string or csv)
    //    If not set, the ID3 info is used
    //
    public    function    set_option_artists ($artists)
    {
        $this->options['artists'] = $artists;
    }



    //
    //    Range 0 to 100 default 60
    //
    public    function    set_option_initialvolume ($initialvolume)
    {
        $this->options['initialvolume'] = $initialvolume;
    }



    //
    //    Default 'no'
    //
    public    function    set_option_loop ($loop)
    {
        $this->options['loop'] = $loop;
    }



    //
    //    'no': player is always open
    //
    public    function    set_option_animation ($animation)
    {
        $this->options['animation'] = $animation;
    }



    //
    //    'yes': remaining track time, not ellapsed
    //
    public    function    set_option_remaining ($remaining)
    {
        $this->options['remaining'] = $remaining;
    }



    //
    //    'yes': disables the track information
    //
    public    function    set_option_noinfo ($noinfo)
    {
        $this->options['noinfo'] = $noinfo;
    }



    //
    //    Buffer time in seconds, default 5
    //
    public    function    set_option_buffer ($buffer)
    {
        $this->options['buffer'] = $buffer;
    }



    //
    //    'yes': matches the page background
    //
    public    function    set_option_transparentpagebg ($transparentpagebg)
    {
        $this->options['transparentpagebg'] = $transparentpagebg;
    }



    //
    //    Use this if the above is 'no'
    //
    public    function    set_option_pagebg ($pagebg)
    {
        $this->options['pagebg'] = $pagebg;
    }



    //
    //    Background color like '777777'
    //
    public    function    set_option_bg ($bg)
    {
        $this->options['bg'] = $bg;
    }



    //
    //    Text color like '777777'
    //
    public    function    set_option_text ($text)
    {
        $this->options['text'] = $text;
    }



    //
    //    Speaker icon/Volume control background color like '777777'
    //
    public    function    set_option_leftbg ($leftbg)
    {
        $this->options['leftbg'] = $leftbg;
    }



    //
    //    Speaker icon color like '777777'
    //
    public    function    set_option_lefticon ($lefticon)
    {
        $this->options['lefticon'] = $lefticon;
    }



    //
    //    Volume track color like '777777'
    //
    public    function    set_option_voltrack ($voltrack)
    {
        $this->options['voltrack'] = $voltrack;
    }



    //
    //    Volume slider color like '777777'
    //
    public    function    set_option_volslider ($volslider)
    {
        $this->options['volslider'] = $volslider;
    }



} 