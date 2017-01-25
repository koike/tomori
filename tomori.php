<?php

require_once 'lib/loader.php';

use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

Streaming::start();
