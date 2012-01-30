<?php


namespace pat;

\set_include_path(\get_include_path() . PATH_SEPARATOR
        . \realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'));

function __autoload($className)
{
    $classFile = str_replace('\\', '/', $className);
    @include $classFile . '.php';
    
    if (false === class_exists($className))
    {
        throw new \Exception('Could not load ' . $className);
    }
}

$success = \spl_autoload_register('pat\__autoload');

if (false == $success)
{
    throw new \ErrorException('Could not register pat\__autoload to autoloader');
}

?>
