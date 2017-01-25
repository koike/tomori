<?php

class Analyze
{
    private $url,
            $html,
            $is_mallicious,
            $rate,
            $result;
    
    public function __construct(string $url)
    {
        $this->url = $url;
        $this->html = '';
        $this->is_mallicious = false;
        $result = null;
    }

    public function analyze(array $response) : float
    {
        $status = $response['status'];
        $html = $response['body'];
        $this->html = $html;
        $this->rate = 0;

        if($status >= 200 && $status < 400)
        {
            // spanのtopが大きなマイナス値
            if(preg_match('/<span style="position:absolute; top:-([0-9]{3,4})px/', $html))
            {
                $this->rate += 1;
            }
            
            // iframeのsrcが小文字.大文字.大文字からなるドメイン
            if(preg_match('/iframe src="http:\/\/([a-z0-9]+)\.([A-Z0-9\-_]+)\.([A-Z0-9\-_]+)/', $html))
            {
                $this->rate += 1;
            }
            
            // bodyがすぐに閉じられる
            if(preg_match('/<body> <\/body>/', $html))
            {
                $this->rate += 1;
            }
            
            // "iframe"という値を持つ変数
            if(preg_match('/var ([a-zA-Z]{5,10}) = "iframe"/', $html))
            {
                $this->rate += 1;
            }
            
            // style.borderが0px
            if(preg_match('/style\.border = "0px"/', $html))
            {
                $this->rate += 0.5;
            }
            
            // frameBorderが0
            if(preg_match('/frameBorder = "0"/', $html))
            {
                $this->rate += 0.5;
            }
            
            // setAttributeでframeBorderを0
            // if(preg_grep('/setAttribute\("frameBorder", "0"\)/', $html))
            // {
            //     $this->rate += 0.5;
            // }
            
            // 小文字.大文字.大文字からなるドメイン
            if(preg_match('/http:\/\/([a-z0-9]+)\.([A-Z0-9\-_]+)\.([A-Z0-9\-_]+)/', $html))
            {
                $this->rate += 0.5;
            }
        }

        if($this->rate >= 2)
        {
            $this->is_mallicious = true;
        }
        return $this->rate;
    }

    public function register_db()
    {
        if(!file_exists('html'))
        {
            mkdir('html');
        }
        $file = 'html/' . date('Y-m-d_H-i-s') . '.html';
        file_put_contents($file, $this->html);
        
        DB::table('HTML')
        ->insert
        (
            [
                'url'           =>  $this->url,
                'html'          =>  $file,
                'created_at'    =>  date('Y-m-d H:i:s')
            ]
        );

        DB::table('RESULT')
        ->insert
        (
            [
                'url'           =>  $this->url,
                'rate'          =>  $this->rate,
                'description'   =>  'Exploit Kit',
                'created_at'    =>  date('Y-m-d H:i:s')
            ]
        );
    }

    public function exclude_url() : bool
    {
        $url = $this->url;
        
        if(strpos(substr($url, 0, strlen('https://twitter.com/')), '://twitter.com/') !== false)
        {
            return false;
        }
        if(strpos(substr($url, 0, strlen('https://itunes.apple.com/')), '://itunes.apple.com/') !== false)
        {
            return false;
        }
        if(strpos(substr($url, 0, strlen('https://www.youtube.com/')), '://www.youtube.com/') !== false)
        {
            return false;
        }
        if(strpos(substr($url, 0, strlen('https://youtube.com/')), '://youtube.com/') !== false)
        {
            return false;
        }
        if(strpos(substr($url, 0, strlen('https://youtu.be/')), '://youtu.be/') !== false)
        {
            return false;
        }
        if(strpos(substr($url, 0, strlen('https://www.instagram.com/')), '://www.instagram.com/') !== false)
        {
            return false;
        }
        if(strpos(substr($url, 0, strlen('https://www.news24.com/')), '://www.news24.com/') !== false)
        {
            return false;
        }
        if(strpos(substr($url, 0, strlen('https://facebook.com/')), '://facebook.com/') !== false)
        {
            return false;
        }
        if(strpos(substr($url, 0, strlen('https://fb.me/')), '://fb.me/') !== false)
        {
            return false;
        }
        if(strpos(substr($url, 0, strlen('https://m.youtube.com/')), '://m.youtube.com/') !== false)
        {
            return false;
        }
        if(strpos(substr($url, 0, strlen('https://amazon.co./')), '://amazon.co./') !== false)
        {
            return false;
        }
        if(strpos(substr($url, 0, strlen('https://www.amazon.co./')), '://www.amazon.co./') !== false)
        {
            return false;
        }
        if(strpos(substr($url, 0, strlen('https://ameblo.jp/')), '://ameblo.jp/') !== false)
        {
            return false;
        }

        return true;
    }

    public function get_url()
    {
        return $this->url;
    }
}
