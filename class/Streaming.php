<?php

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
                $tomori = new Analyze($url);

                // 解析する意味のあるURLか
                if(!$tomori->exclude_url())
                {
                    return;
                }

                // 既に解析していないか
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
