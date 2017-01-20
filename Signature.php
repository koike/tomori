<?php

require_once('Database.php');

class Signature
{
    public static function get()
    {
        return json_decode(json_encode(DB::table('SIGNATURE')->get()), true);
    }
}
