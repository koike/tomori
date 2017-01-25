<?php

require_once 'vendor/fennb/phirehose/lib/Phirehose.php';
require_once 'vendor/fennb/phirehose/lib/OauthPhirehose.php';

require_once 'Request.php';
require_once 'Analyze.php';
require_once 'Notificate.php';
require_once 'Database.php';

class SampleConsumer extends OauthPhirehose
{
    public function enqueueStatus($status)
    {
        $data = json_decode($status, true);
        if (is_array($data))
        {
            $url = isset($data['entities']['urls'][0]['expanded_url']) ? $data['entities']['urls'][0]['expanded_url'] : null;
            if($url != null)
            {
                if(strpos(substr($url, 0, strlen('https://twitter.com/')), '://twitter.com/') !== false)
                {
                    return;
                }
                if(strpos(substr($url, 0, strlen('https://itunes.apple.com/')), '://itunes.apple.com/') !== false)
                {
                    return;
                }
                if(strpos(substr($url, 0, strlen('https://www.youtube.com/')), '://www.youtube.com/') !== false)
                {
                    return;
                }
                if(strpos(substr($url, 0, strlen('https://youtube.com/')), '://youtube.com/') !== false)
                {
                    return;
                }
                if(strpos(substr($url, 0, strlen('https://youtu.be/')), '://youtu.be/') !== false)
                {
                    return;
                }
                if(strpos(substr($url, 0, strlen('https://www.instagram.com/')), '://www.instagram.com/') !== false)
                {
                    return;
                }
                if(strpos(substr($url, 0, strlen('https://www.news24.com/')), '://www.news24.com/') !== false)
                {
                    return;
                }
                if(strpos(substr($url, 0, strlen('https://facebook.com/')), '://facebook.com/') !== false)
                {
                    return;
                }
                if(strpos(substr($url, 0, strlen('https://fb.me/')), '://fb.me/') !== false)
                {
                    return;
                }
                if(strpos(substr($url, 0, strlen('https://m.youtube.com/')), '://m.youtube.com/') !== false)
                {
                    return;
                }
                if(strpos(substr($url, 0, strlen('https://amazon.co./')), '://amazon.co./') !== false)
                {
                    return;
                }
                if(strpos(substr($url, 0, strlen('https://www.amazon.co./')), '://www.amazon.co./') !== false)
                {
                    return;
                }
                if(strpos(substr($url, 0, strlen('https://ameblo.jp/')), '://ameblo.jp/') !== false)
                {
                    return;
                }

                $url_accessed = json_decode
                (
                    json_encode
                    (
                        DB::table('URL')->where('url', $url)->get()
                    ),
                    true
                );

                if(empty($url_accessed))
                {
                    DB::table('URL')
                    ->insert
                    (
                        [
                            'url'           =>  $url,
                            'created_at'    =>  date('Y-m-d H:i:s')
                        ]
                    );

                    $response = Request::get($url);
                    $tomori = new Analyze($url);
                    $rate = $tomori->analyze($response);
                    
                    if($rate > 1)
                    {
                        $tomori->register_db();
                        Notificate::slack($tomori);
                    }
                    echo '[' . $rate . '] ' . $url . PHP_EOL;
                }
            }
        }
    }
}

class Streaming
{
    public static function start()
    {
        define('TWITTER_CONSUMER_KEY', getenv('TWITTER_CONSUMER_KEY'));
        define('TWITTER_CONSUMER_SECRET', getenv('TWITTER_CONSUMER_SECRET'));
        define('OAUTH_TOKEN', getenv('OAUTH_TOKEN'));
        define('OAUTH_SECRET', getenv('OAUTH_SECRET'));
        
        $sc = new
        // FilterTrackConsumer
        SampleConsumer
        (
            OAUTH_TOKEN,
            OAUTH_SECRET,
            // Phirehose::METHOD_FILTER
            Phirehose::METHOD_SAMPLE
        );
        // $sc->setTrack(['https://t.co']);
        $sc->consume();
    }
}
