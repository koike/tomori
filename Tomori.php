<?php

require_once('Database.php');

class Tomori
{
    private $url,
            $html,
            $is_mallicious,
            $result;
    
    public function __construct(string $url, string $db_filename = 'tomori.db')
    {
        $this->url = $url;
        $this->html = '';
        $this->is_mallicious = false;
        $result = null;
        define('DB_FILENAME', $db_filename);
    }

    public function analyze(array $response) : bool
    {
        $status = $response['status'];
        if($status >= 200 && $status <= 400)
        {
            $sig = Signature::get();
            for($i=0; $i<count($sig); $i++)
            {
                $sig[$i]['is_mallicious'] = preg_match($sig[0]['pattern'], $html);
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

    public function register_db() : void
    {
        DB::table('HTML')
        ->insert
        (
            [
                'url'           =>  $this->url,
                'html'          =>  $this->html,
                'created_at'    =>  date('Y-m-d H:i:s')
            ]
        );

        for($i=0; $i<count($this->result); $i++)
        {
            DB::table('RESULT')
            ->insert
            (
                [
                    'url'           =>  $this->url,
                    'pattern'       =>  $this->result[$i]['pattern'],
                    'description'   =>  $this->result[$i]['description'],
                    'is_mallicious' =>  $this->is_mallicious,
                    'created_at'    =>  date('Y-m-d H:i:s')
                ]
            );
        }
    }
}
