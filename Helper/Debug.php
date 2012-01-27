<?php

namespace pat\Helper;

class Debug {

    public static function init() {
        ini_set('display_errors', true);
        error_reporting(E_ALL);
    }

}

?>
