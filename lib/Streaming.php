<?php

class SampleConsumer extends OauthPhirehose
{
    public function enqueueStatus($status)
    {
        $data = json_decode($status, true);
        if (is_array($data))
        {
            $urls = $data['entities']['urls'] ?? null;
            if($urls == null)
            {
                return;
            }
            foreach($urls as $url)
            {
                $url = $url['expanded_url'] ?? null;
                if($url == null)
                {
                    break;
                }

                // urlを小文字に変換
                $url = mb_strtolower($url);

                // 無限に展開しないように
                $count = 0;
                // 短縮URLの場合は再帰的に展開する
                while
                (
                    // goo.gl
                    preg_match("/^https?:\/\/goo\.gl\/[a-zA-Z0-9]/", $url) ||
                    // bit.ly
                    preg_match("/^https?:\/\/bit\.ly\/[a-zA-Z0-9]/", $url) ||
                    // ift.tt
                    preg_match("/^https?:\/\/ift\.tt\/[a-zA-Z0-9]/", $url) ||
                    // ln.is
                    preg_match("/^https?:\/\/ln\.is\/[a-zA-Z0-9]/", $url) ||
                    // dlvr.it
                    preg_match("/^https?:\/\/dlvr\.it\/[a-zA-Z0-9]/", $url) ||
                    // ow.ly
                    preg_match("/^https?:\/\/ow\.ly\/[a-zA-Z0-9]/", $url) ||
                    // j.mp
                    preg_match("/^https?:\/\/j\.mp\/[a-zA-Z0-9]/", $url) ||
                    // buff.ly
                    preg_match("/^https?:\/\/buff\.ly\/[a-zA-Z0-9]/", $url)
                )
                {
                    // 3回以上展開すると無限にループする可能性が高いので中断
                    if($count == 3)
                    {
                        break;
                    }

                    $extract = Request::extract_url($url);
                    if($extract == null || $extract == $url)
                    {
                        break;
                    }
                    $url = $extract;

                    $count++;
                }

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
                    $tomori->analyze($response);
                    
                    if($tomori->get_is_mallicious())
                    {
                        $tomori->register_db($data);
                        Notificate::slack($tomori);
                        echo '[*] ' . $url . PHP_EOL;
                    }
                    else
                    {
                        echo '[-] ' . $url . PHP_EOL;
                    }
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
