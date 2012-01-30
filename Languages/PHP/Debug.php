<?php

namespace pat\Languages\PHP;

class Debug {

    public static function init() {
        \error_reporting(-1);
        \ini_set('display_errors', true);
        \ini_set('display_errors_startup', true);
    }
    
    public static function dump()
    {
        echo '<pre>';
        \ob_start();
        $success = \call_user_func_array('var_dump', \func_get_args());
        
        if (false === $success)
        {
            throw new \ErrorException('Can not dump variables');
        }
        
        echo \htmlspecialchars(\ob_get_clean());
        echo '</pre>';
    }

}

?>
