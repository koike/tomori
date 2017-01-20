<?php

require_once 'vendor/autoload.php';
require_once 'Streaming.php';

use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

Streaming::start();
