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

    public static function extract_url(string $url)
    {
        if(!is_string($url) || strlen($url) == 0)
        {
            return null;
        }

        $ua = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36';
        $ref = $url;

        try
        {
            $opts['http'] =
            [
                'method' => 'GET',
                'header' => 'User-Agent: ' . $ua,
                'timeout' => 5
            ];
            $context = stream_context_create($opts);
            file_get_contents($url, false, $context);
            $headers = $http_response_header;

            $location = [];
            foreach($headers as $line)
            {
                if(preg_match('/^HTTP\/1\.[0-1] 4/', $line))
                {
                    return null;
                }
                if(preg_match('/^Location:/', $line))
                {
                    array_push($location, trim(str_replace('Location: ', '', $line)));
                }
            }
            if(empty($location))
            {
                return $url;
            }

            $domain = [];
            foreach($location as $l)
            {
                if(preg_match('/^https?:\/\//', $l))
                {
                    $l = parse_url($l, PHP_URL_SCHEME) . '://' . parse_url($l, PHP_URL_HOST);
                    array_push($domain, $l);
                }
            }

            $location = end($location);
            if(!preg_match('/^https?:\/\//', $location))
            {
                $location = end($domain) . $location;
            }

            return $location;
        }
        catch(\Exception $e)
        {
            echo $e->getMessage();
            return null;
        }
    }
}
