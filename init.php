<?php


namespace pat;

\set_include_path(\get_include_path() . PATH_SEPARATOR
        . \realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'));

function __autoload($class)
{
    return ((include $class . '.php') === 'OK');
}

\spl_autoload_register('pat\__autoload');

?>
