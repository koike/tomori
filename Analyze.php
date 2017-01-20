<?php

$url = '';

$response = Request::get($url);

$tomori = new Tomori($url);
$is_mallicious = $tomori->analyze($response);

if(!$is_mallicious)
{
    $tomori->register_db();
    Notificate::slack($tomori);
}
