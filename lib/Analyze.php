<?php

class Analyze
{
    private $url,
            $html,
            $is_mallicious,
            $result,
            $description,
            $gist_url;
    
    public function __construct(string $url)
    {
        $this->url = $url;
        $this->html = null;
        $this->is_mallicious = false;
        $this->result = null;
        $this->description = null;
        $this->gist_url = null;
    }

    public function analyze(array $response) : bool
    {
        $status = $response['status'];
        $html = $response['body'];
        $this->html = $html;

        if($status >= 200 && $status < 400)
        {
            $pd = PseudoDarkleech::analyze($html);
            if($pd)
            {
                $this->description = 'PseudoDarkleech';
            }
            $ei = EITest::analyze($html);
            if($ei)
            {
                $this->description = 'EITest';
            }
            $ei2 = EITest::analyze($html);
            if($ei2)
            {
                $this->description = 'EITest';
            }
            $af = Afraidgate::analyze($html);
            if($af)
            {
                $this->description = 'Afraidgate';
            }

            if($pd || $ei || $ei2 || $af)
            {
                $this->is_mallicious = true;
                return true;
            }
        }

        $this->is_mallicious = false;
        return false;
    }

    public function register_db($tweet = null)
    {
        // データをgistにPOSTする
        $files =
        [
            "data.html" =>
            [
                'content'   =>  $this->html
            ],
            'tweet.json' =>
            [
                'content'   =>  json_encode($tweet)
            ]
        ];
        $this->gist_url = Gist::create('[' . $this->descripntion . ']' . $this->url, false, $files);
        
        DB::table('GIST')
        ->insert
        (
            [
                'url'           =>  $this->url,
                'gist'          =>  $this->gist_url,
                'created_at'    =>  date('Y-m-d H:i:s')
            ]
        );

        DB::table('RESULT')
        ->insert
        (
            [
                'url'           =>  $this->url,
                'description'   =>  $this->description,
                'created_at'    =>  date('Y-m-d H:i:s')
            ]
        );
    }

    public function exclude_url() : bool
    {
        $url = $this->url;

        // ドメインのwhite listを取得
        $white_list = json_decode
        (
            json_encode
            (
                DB::table('WHITE_LIST')->get()
            ),
            true
        );
        // white listに含まれている場合は解析する必要なし
        foreach($white_list as $domain)
        {
            if
            (
                strpos
                (
                    substr($url, 0, strlen('https://' . $domain['domain'])) . '/',
                    '://' . $domain['domain'] . '/'
                )
                !== false
            )
            {
                return false;
            }
        }
        
        return true;
    }

    public function get_url()
    {
        return $this->url;
    }

    public function get_is_mallicious()
    {
        return $this->is_mallicious;
    }

    public function get_gist_url()
    {
        return $this->gist_url;
    }

    public function get_description()
    {
        return $this->description;
    }
}
