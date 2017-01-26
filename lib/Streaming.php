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
                $url_accessed = DB::table('URL')->where('url', $url)->get();

                if(empty($url_accessed))
                {
                    $date = date('Y-m-d H:i:s');

                    DB::table('URL')
                    ->insert
                    (
                        [
                            'url'           =>  $url,
                            'created_at'    =>  $date
                        ]
                    );

                    $response = Request::get($url);
                    $tomori = new Analyze($url);
                    $rate = $tomori->analyze($response);
                    
                    if($tomori->get_is_mallicious())
                    {
                        $tomori->register_db();
                        Notificate::slack($tomori);
                        User::register($data, $date);
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
        SampleConsumer
        (
            OAUTH_TOKEN,
            OAUTH_SECRET,
            Phirehose::METHOD_SAMPLE
        );
        $sc->consume();
    }
}
