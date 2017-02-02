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
        ob_start();
        var_dump($tweet);
        $tweet_dump = ob_get_contents();
        ob_end_clean();
        // データをgistにPOSTする
        $files =
        [
            "data.html" =>
            [
                'content'   =>  base64_encode($this->html) . ''
            ],
                'tweet.json' =>
            [
                'content'   =>  base64_encode($tweet_dump) . ''
            ]
        ];
        $this->gist_url = Gist::create('[' . $this->description . '] ' . $this->url, false, $files);
        
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
    
    public static function extract_url(string $url) : string
    {
        try
        {
            $header = get_headers($url, 1);
            if(isset($header['Location']))
            {
                $url = $header['Location'];
                if(is_array($url))
                {
                    $url = end($url);
                }
            }
            return $url;
        }
        catch(\Exception $e)
        {
            return $url;
        }
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
            if(preg_match('/https?:\/\/' . str_replace('.', '\.', $domain['domain']) . '\//', $url))
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