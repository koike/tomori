<?php

require_once 'lib/loader.php';

use Dotenv\Dotenv;

function error_handler($no, $str, $file, $line)
{
    exit(-1);
}

function exception_handler($e)
{
    Notificate::exception($e);
    exit(-1);
}

set_error_handler('error_handler');
set_exception_handler('exception_handler');

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

Streaming::start();
