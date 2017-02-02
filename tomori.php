<?php

require_once 'lib/loader.php';

use Dotenv\Dotenv;

function error_handler($no, $str, $file, $line)
{
    Notificate::error($no, $str, $file, $line);
    exit(-1);
}

function exception_handler($e)
{
    $class = get_class($e);
    if($class == 'Exception')
    {
        Notificate::exception($e);
    }
    else if($class == 'Error')
    {
        echo '[exception_handler] Error given...';
    }
    exit(-1);
}

function shutdown_handler()
{
    Notificate::shutdown();
}

set_error_handler('error_handler');
set_exception_handler('exception_handler');
register_shutdown_function('shutdown_handler');

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

Streaming::start();
