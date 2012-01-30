<?php

namespace pat\Languages\PHP;

/**
 * Description of CLI
 *
 * @author rmp
 */
class CLI {
    
    const COLOR_BLACK = "0;30";
    const COLOR_DARKGRAY = "1;30";
    const COLOR_RED   = "0;31";
    const COLOR_LIGHTRED   = "1;31";
    const COLOR_GREEN = "0;32";
    const COLOR_LIGHTGREEN = "1;32";
    const COLOR_BROWN = "0;33";
    const COLOR_YELLOW = "1;33";
    const COLOR_BLUE = "0;34";
    const COLOR_LIGHTBLUE = "1;34";
    const COLOR_PURPLE = "0;35";
    const COLOR_LIGHTPURPLE = "1;35";
    const COLOR_CYAN = "0;36";
    const COLOR_LIGHTCYAN = "1;36";
    const COLOR_LIGHTGRAY = "0;37";
    const COLOR_WHITE = "1;37";
    
    const BACKGROUND_BLACK = "40";
    const BACKGROUND_RED = "41";
    const BACKGROUND_GREEN = "42";
    const BACKGROUND_YELLOW = "43";
    const BACKGROUND_BLUE = "44";
    const BACKGROUND_MAGENTA = "45";
    const BACKGROUND_CYAN = "46";
    const BACKGROUND_LIGHTGRAY = "47";
    
    public static function getColoredString(
            $text,
            $textColor = self::COLOR_WHITE,
            $backgroundColor = null)
    {
        $getColoredString = "\033[" . $textColor . "m";
        
        if (null !== $backgroundColor)
        {
            $getColoredString .= "\033[" . $backgroundColor . "m";
        }
        
        $getColoredString .= $text;         // text
        $getColoredString .= "\033[0m";     // reset colors
        
        return  $getColoredString;
    }
}

?>