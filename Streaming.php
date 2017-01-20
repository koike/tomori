<?php

require_once 'vendor/fennb/phirehose/lib/Phirehose.php';
require_once 'vendor/fennb/phirehose/lib/OauthPhirehose.php';

require_once 'Request.php';
require_once 'Analyze.php';
require_once 'Notificate.php';

class SampleConsumer extends OauthPhirehose
{
    public function enqueueStatus($status)
    {
        $data = json_decode($status, true);
        if (is_array($data))
        {
            $url = $data['entities']['urls'][0]['expanded_url'] ?? null;
            if($url != null)
            {
                $response = Request::get($url);
                $tomori = new Analyze($url);
                $is_mallicious = $tomori->analyze($response);
                
                if($is_mallicious)
                {
                    $tomori->register_db();
                    Notificate::slack($tomori);

                    echo '[!] ' . $url . PHP_EOL;
                }
                else
                {
                    echo '[-] ' . $url . PHP_EOL;
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
