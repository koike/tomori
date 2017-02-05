<?php

class PseudoDarkleech
{
    public static function analyze(string $html) : bool
    {
        $rate = 0;

        // spanのtopが大きなマイナス値
        if(preg_match('/<span style="position:absolute; top:-([0-9]{3,4})px/', $html))
        {
            $rate += 1;
        }
        
        // iframeのsrcが小文字.大文字.大文字からなるドメイン
        if(preg_match('/iframe src="https?:\/\/([a-z0-9]+)\.([a-zA-Z0-9\-_]+)\.([A-Z0-9\-_]+)/', $html))
        {
            $rate += 1;
        }

        // biw, ct, br_fl, tuif, oq
        if(strpos($html, 'biw=') !== false)
        {
            $rate += 0.2;
        }
        if(strpos($html, 'ct=') !== false)
        {
            $rate += 0.2;
        }
        if(strpos($html, 'br_fl=') !== false)
        {
            $rate += 0.2;
        }
        if(strpos($html, 'tuif=') !== false)
        {
            $rate += 0.2;
        }
        if(strpos($html, 'oq=') !== false)
        {
            $rate += 0.2;
        }

        if($rate >= 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
