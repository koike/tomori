<?php

class User
{
    public static function register(array $data, string $date)
    {
        if(!file_exists('json'))
        {
            mkdir('json');
        }
        
        file_put_contents('json/' . str_replace(' ', '', str_replace(':', '_', $date)) . '.json', json_encode($data));
    }
}
