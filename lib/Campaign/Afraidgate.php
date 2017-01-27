<?php

class Afraidgate
{
    public static function analyze(string $html) : bool
    {
        /*
            htmlだけでは判断出来ない
            html内で読み込まれているjsに
            - document.write('<div style="position:absolute;
            - <iframe src="http://小文字.大文字.大文字
            が含まれていて, かつ
            jsをホストしているドメインのNSがns1.afraid.org
        */
        return false;
    }
}
