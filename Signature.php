<?php

require_once('Database.php');

class Signature
{
    public static function get()
    {
        return DB::table('SIGNATURE')->get()->toArray();
    }
}
