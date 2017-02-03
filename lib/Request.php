<?php

use GuzzleHttp\Client;

class Request
{
    public static function get(string $url, $ua = null, $ref = null) : array
    {
        if(!is_string($url) || strlen($url) == 0)
        {
            return
            [
                'status'    =>  400,
                'type'      =>  null,
                'body'      =>  null
            ];
        }

        $ua = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648)';
        $ref = $url;

        $client = new Client(['verify' => false]);
        try
        {
            $response = $client->request
            (
                'GET',
                $url,
                [
                    'headers'   =>
                    [
                        'User-Agent'    =>  $ua,
                        'Referer'       =>  $ref
                    ],
                    'timeout'   =>  5
                ]
            );

            return
            [
                'status'    =>  $response->getStatusCode(),
                'type'      =>  $response->getHeader('Content-Type'),
                'body'      =>  $response->getBody()
            ];
        }
        catch(\Exception $e)
        {
            return
            [
                'status'    =>  500,
                'type'      =>  null,
                'body'      =>  null
            ];
        }
    }

    public static function extract_url(string $url) : string
    {
        if(!is_string($url) || strlen($url) == 0)
        {
            echo '0' . PHP_EOL;
            return $url;
        }

        $ua = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648)';
        $ref = $url;

        echo '1' . PHP_EOL;
        $client = new Client(['verify' => false]);
        try
        {
            $response = $client->request
            (
                'GET',
                $url,
                [
                    'headers'   =>
                    [
                        'User-Agent'    =>  $ua,
                        'Referer'       =>  $ref
                    ],
                    'timeout'   =>  5
                ]
            );

            echo '2' . PHP_EOL;

            $headers = $response->getHeaders();
            $location = $headers['Location'] ?? null;
            if($location == null)
            {
                echo '3' . PHP_EOL;
                return $url;
            }

            if(is_array($location))
            {
                $location = end($location);
            }

            if(!preg_match('/^https?:\/\//', $location))
            {
                $location = $url . $location;
            }

            echo '4' . PHP_EOL;
            return $location;
        }
        catch(\Exception $e)
        {
            echo '5' . PHP_EOL;
            return $url;
        }
    }
}
