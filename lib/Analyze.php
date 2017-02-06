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
            $ei2 = EITest2::analyze($html);
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
        DB::table('RESULT')
        ->insert
        (
            [
                'url'           =>  $this->url,
                'description'   =>  $this->description,
                'created_at'    =>  date('Y-m-d H:i:s')
            ]
        );

        // ドメインに紐付いてるIPの情報を得る
        $subdomain = parse_url($this->url, PHP_URL_HOST);
        $ip_addr['subdomain'] = gethostbynamel($subdomain);

        // サブドメインの場合はメインドメインの情報も得る
        $element = explode('.', $subdomain);
        if(count($element) >= 3)
        {
            $tld = end($element);
            $domain = $element[count($element)-2] . '.' . $tld;
            $ip_addr['domain'] = gethostbynamel($domain);
        }
        
        // データをgistにPOSTする
        $files =
        [
            "data.html" =>
            [
                'content'   =>  $this->html . ''
            ],
            'tweet.json' =>
            [
                'content'   =>  json_encode($tweet) . ''
            ],
            'ip.json' =>
            [
                'content'   =>  json_encode($ip_addr) . ''
            ]
        ];

        ob_start();
        var_dump($files);
        $files_dump = ob_get_contents();
        ob_end_clean();
        if(!file_exists('log'))
        {
            mkdir('log');
        }
        file_put_contents('log/' . date('Y_m_d_H_i_s') . '.txt', $files_dump);

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

        // 拡張子が明らかにhtmlではないものは弾く
        if
        (
            preg_match('/\.jpg$/', $url) ||
            preg_match('/\.png$/', $url) ||
            preg_match('/\.bmp$/', $url) ||
            preg_match('/\.zip$/', $url) ||
            preg_match('/\.rar$/', $url) ||
            preg_match('/\.exe$/', $url) ||
            preg_match('/\.mp3$/', $url) ||
            preg_match('/\.mp4$/', $url) ||
            preg_match('/\.json$/', $url)
        )
        {
            return false;
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