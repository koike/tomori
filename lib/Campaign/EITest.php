<?php

class EITest
{
    public static function analyze(string $html) : bool
    {
        $rate = 0;
        
        // <body> </body>
        if(preg_match('/<body> <\/body>/', $html))
        {
            $rate += 1;
        }

        // iframeという文字列を持つ変数
        if(preg_match('/var ([a-zA-Z]{4,8}) = "iframe"/', $html))
        {
            $rate += 1;
        }

        // style.border = "0px"
        if(preg_match('/style\.border = "0px"/', $html))
        {
            $rate += 0.2;
        }

        // frameBorder = "0"
        if(preg_match('/frameBorder = "0"/', $html))
        {
            $rate += 0.2;
        }

        // setAttribute("frameBorder", "0")
        // if(preg_grep('/setAttribute\("frameBorder", "0"\)/', $html))
        // {
        //     $rate += 0.2;
        // }

        // document.body.appendChild
        if(preg_match('/document\.body\.appendChild/', $html))
        {
            $rate += 0.2;
        }

        // http://小文字.大文字.大文字
        if(preg_match('/https?:\/\/([a-z0-9]+)\.([A-Z0-9\-_]+)\.([A-Z0-9\-_]+)/', $html))
        {
            $rate += 0.5;
        }

        if($rate >= 2)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
