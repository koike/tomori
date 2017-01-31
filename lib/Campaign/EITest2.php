<?php

class EITest2
{
    public static function analyze(string $html) : bool
    {
        $rate = 0;

        // if (!!window.chrome && !!window.chrome.webstore)
        if(preg_match('/if \(!!window\.chrome && !!window\.chrome\.webstore\)/'))
        {
            $rate += 1;
        }

        // <input type='hidden'
        if(preg_match('/<input type=\'hidden\'/'))
        {
            $rate += 1;
        }

        // <div id="popup-container" class="popuo-window gc" style="display:none;">
        if(preg_match('/<div id="popup-container" class="popuo-window gc" style="display:none;">/'))
        {
            $rate += 1;
        }

        if($rate > 2)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
