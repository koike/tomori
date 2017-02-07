<?php

use GuzzleHttp\Client;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Plugin\History\HistoryPlugin;

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
            return $url;
        }

        try
        {
            $client = new GuzzleClient($url);
            $ua = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648)';
            $ref = $url;
            $client->setUserAgent($ua);
            $history = new HistoryPlugin();
            $client->addSubscriber($history);
            $response =
            $client->head
            (
                $url,
                [],
                [
                    'timeout' => 5,
                    'connect_timeout' => 1,
                    'verify' => false
                ]
            )
            ->send();
            if (!$response->isSuccessful())
            {
                return $url;
            }
            
            return $response->getEffectiveUrl();
        }
        catch(\Exception $e)
        {
            return $url;
        }
    }
}
