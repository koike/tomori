<?php

require_once('vendor/fennb/phirehose/lib/Phirehose.php');
require_once('vendor/fennb/phirehose/lib/OauthPhirehose.php');

class FilterTrackConsumer extends OauthPhirehose
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
                
                if(!$is_mallicious)
                {
                    $tomori->register_db();
                    Notificate::slack($tomori);
                }
            }
        }
    }
}

class Streaming
{
    public function __construct()
    {
        define('TWITTER_CONSUMER_KEY', getenv('TWITTER_CONSUMER_KEY'));
        define('TWITTER_CONSUMER_SECRET', getenv('TWITTER_CONSUMER_SECRET'));
        define('TWITTER_ACCESS_TOKEN', getenv('TWITTER_ACCESS_TOKEN'));
        define('TWITTER_ACCESS_TOKEN_SECRET', getenv('TWITTER_ACCESS_TOKEN_SECRET'));
    }
    
    public function start()
    {
        $sc = new FilterTrackConsumer(TWITTER_ACCESS_TOKEN, TWITTER_ACCESS_TOKEN_SECRET, Phirehose::METHOD_FILTER);
        $sc->setTrack(['https://t.co']);
        $sc->consume();
    }
}
