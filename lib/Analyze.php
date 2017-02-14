<?php

class Analyze
{
    private $url,
            $html,
            $is_malicious,
            $result,
            $description,
            $gist_url,
            $js_content;
    
    public function __construct(string $url)
    {
        $this->url = $url;
        $this->html = null;
        $this->is_malicious = false;
        $this->result = null;
        $this->description = null;
        $this->gist_url = null;
        $this->js_content = null;
    }
    
    public function analyze(array $response) : bool
    {
        $status = $response['status'];
        $html = $response['body'];
        $this->html = $html;
        
        if($status >= 200 && $status < 400)
        {
            // load -> /lib/Campaign/*.php
            foreach(glob(__DIR__ . '/Campaign/*.php') as $file)
            {
                if(is_file($file))
                {
                    // get class name
                    $campaign = pathinfo($file)['filename'];

                    // analyze
                    $result = $campaign::analyze($html, $this->url);
                    // format result
                    if(!isset($result['is_malicious']))
                    {
                        $_result = $result;
                        $result = [];
                        $result['is_malicious'] = $_result;
                    }

                    if($result['is_malicious'])
                    {
                        $this->description = $campaign;
                        if(isset($result['js_content']) && $result['js_content'] != null)
                        {
                            $this->description .= '(' . $result['js'] . ')';
                            $this->js_content = $result['js_content'];
                        }
                        $this->is_malicious = true;
                        return true;
                    }
                }
            }
        }
        
        $this->is_malicious = false;
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
        if($this->js_content != null)
        {
            $files =
            [
                "data.html" =>
                [
                    'content'   =>  $this->html . ''
                ],
                'code.js' =>
                [
                    'content'   =>  $this->js_content . ''
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
        }
        else
        {
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
        }

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
        $white_list = DB::table('WHITE_LIST')->get()->all();

        // white listに含まれている場合は解析する必要なし
        foreach($white_list as $domain)
        {
            if(parse_url($url, PHP_URL_HOST) === $domain['domain'])
            {
                return false;
            }
            // if(preg_match('/https?:\/\/' . str_replace('.', '\.', $domain['domain']) . '\//', $url))
            // {
            //     return false;
            // }
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
    
    public function get_is_malicious()
    {
        return $this->is_malicious;
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