<?php

// read config
$pat = parse_ini_file('pat.ini', true);

// get helper for CLI
include realpath(dirname(__FILE__)
                . DIRECTORY_SEPARATOR . '..'
                . DIRECTORY_SEPARATOR . 'Languages'
                . DIRECTORY_SEPARATOR . 'PHP'
                . DIRECTORY_SEPARATOR . 'CLI.php');

// cut off scriptname
array_shift($argv);

use pat\Languages\PHP\CLI as CLI;

/*
 * Common functions
 */

function react($hint) {
    echo CLI::getColoredString($hint, CLI::COLOR_LIGHTRED);
}

function command($command) {
    return CLI::getColoredString($command, CLI::COLOR_LIGHTGREEN);
}

function example($example) {
    return CLI::getColoredString($example, CLI::COLOR_LIGHTGRAY);
}

function makeDirectory($name) {
    if (!is_dir($name)) {
        echo CLI::getColoredString("Creating $name/...\n");
        mkdir($name);
    }
}

function dice() {
    return func_get_arg(mt_rand(0, count(func_num_args() - 1)));
}

function minor($text) {
    return CLI::getColoredString($text, CLI::COLOR_LIGHTGRAY);
}

function sorry($text) {
    return CLI::getColoredString('Sorry: ', CLI::COLOR_LIGHTRED)
            . $text;
}

/*
 * CLI functions 
 */

function patCLI_create() {
    patCLI_help();
}

function patCLI_create_project($rootDirectory = null) {
    
    
    
    if (null === $rootDirectory) {
        echo sorry("Do you want to create the project here?");
        echo example(" [Y/n] ");
        fscanf(STDIN, "%s\n", $createHere);

        if (strtolower($createHere) != "y") {
            react(
                    dice("What a pity! :("
                            , "You betrait me, right? :(")
            );
            echo "\n\n";
            exit();
        }

        $rootDirectory = getcwd();
    }
    
    echo "Creating directories...\n";

    makeDirectory($rootDirectory . DIRECTORY_SEPARATOR . 'app');
    makeDirectory($rootDirectory . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Controller');
    makeDirectory($rootDirectory . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Model');
    makeDirectory($rootDirectory . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'View');

    makeDirectory($rootDirectory . DIRECTORY_SEPARATOR . 'config');
    makeDirectory($rootDirectory . DIRECTORY_SEPARATOR . 'doc');
    makeDirectory($rootDirectory . DIRECTORY_SEPARATOR . 'library');
    makeDirectory($rootDirectory . DIRECTORY_SEPARATOR . 'logs');
    makeDirectory($rootDirectory . DIRECTORY_SEPARATOR . 'public');
    makeDirectory($rootDirectory . DIRECTORY_SEPARATOR . 'test');
    makeDirectory($rootDirectory . DIRECTORY_SEPARATOR . 'tmp');
    makeDirectory($rootDirectory . DIRECTORY_SEPARATOR . 'vendor');
    
    echo react("Success: ") . "Feel free to begin.\n";
}

function patCLI_help() {
    global $pat;

    echo sorry("What did you want to do?");

    echo "\n\n";

    echo minor("\tPlease use: pat command [sub-command...]");

    echo "\n\n";

    echo "\t" . command("create\t");
    echo " - Create a project, controller, view or model ";
    echo example("(e.g. pat create project)");

    echo "\n";

    echo "\t" . command("version\t");
    echo " - Get version ";
    echo example("(currently: " . $pat['general']['version'] . ")");

    echo "\n\n";

    echo minor("\tAppend the commmands to get a help like this again (e.g. pat create).\n");
}

function patCLI_version() {
    global $pat;

    echo "PHP Application Toolkit: ";
    echo pat\Languages\PHP\CLI::getColoredString(
            $pat['general']['version'], pat\Languages\PHP\CLI::COLOR_LIGHTBLUE);
}

/*
 * Mapping 
 */

$functionName = "patCLI";
while (count($argv) > 0) {
    $segment = strtolower($argv[0]);
    if (function_exists($functionName . '_' . $segment)) {
        $functionName .= '_' . $segment;
        array_shift($argv);
    } else {
        break;
    }
}

if ($functionName == "patCLI") {
    patCLI_help();
} else {
    $success = call_user_func_array($functionName, $argv);
    // todo: D20120130 recursive trace for help function
}

echo "\n";
?>
