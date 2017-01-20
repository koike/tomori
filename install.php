<?php

$composer = 'composer install';
exec($composer, $arr, $ret);

if(!file_exists('tomori.db'))
{
    $sql = 'sqlite3 tomori.db .read setup.sql';
    exec($sql, $arr, $ret);
}

// ToDo: store data -> db
//
