<?php

require_once 'Database.php';
require_once 'Signature.php';

class Analyze
{
    private $url,
            $html,
            $is_mallicious,
            $result;
    
    public function __construct(string $url)
    {
        $this->url = $url;
        $this->html = '';
        $this->is_mallicious = false;
        $result = null;
    }

    public function analyze(array $response) : bool
    {
        $status = $response['status'];
        $html = $response['body'];
        $this->html = $html;
        if($status >= 200 && $status <= 400)
        {
            $sig = Signature::get();
            for($i=0; $i<count($sig); $i++)
            {
                if($sig[$i]['is_reg'])
                {
                    $sig[$i]['is_mallicious'] = preg_match($sig[$i]['pattern'], $html);
                }
                else
                {
                    $sig[$i]['is_mallicious'] = eval($sig[$i]['pattern']);
                }
                if($sig[$i]['is_mallicious'] && !$this->is_mallicious)
                {
                    $this->is_mallicious = true;
                }
            }
            $this->result = $sig;

            return $this->is_mallicious;
        }
        else
        {
            return false;
        }
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

        for($i=0; $i<count($this->result); $i++)
        {
            if($this->result[$i]['is_mallicious'])
            {
                DB::table('RESULT')
                ->insert
                (
                    [
                        'url'           =>  $this->url,
                        'pattern'       =>  $this->result[$i]['pattern'],
                        'description'   =>  $this->result[$i]['description'],
                        'created_at'    =>  date('Y-m-d H:i:s')
                    ]
                );
            }
        }
    }
}
