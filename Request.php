<?php

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;

class Request
{
    public static function get(string $url, string $ua = null, string $ref = null)
    {
        if(!is_string($url) || strlen($url) == 0)
        {
            return '';
        }

        $ua = $ua ?? 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648)';
        $ref = $ref ?? $url;

        $client = new Client();
        $response = $client->request
        (
            'GET',
            $url,
            [
                'headers'   =>
                [
                    'User-Agent'    =>  $ua,
                    'Referer'       =>  $ref
                ]
            ]
        );

        return
        [
            'status'    =>  $response->getStatusCode(),
            'type'      =>  $response->getHeader('Content-Type'),
            'body'      =>  $response->getBody()
        ];
    }
}
