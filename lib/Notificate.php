<?php

class Notificate
{
    public static function slack(Analyze $tomori)
    {
        $url = $tomori->get_url();
        if($url != null)
        {
            $token = getenv('SLACK_TOKEN');
            $channel = urlencode('#alert');
            $text = urlencode('[Compromised (rate => ' . $tomori->get_rate() . ')] ' . date('Y-m-d H:i:s') . "\n```\n" . $tomori->get_url() . "\n```");
            $url = 'https://slack.com/api/chat.postMessage?token=' .
                    $token .
                    '&channel=' .
                    $channel .
                    '&text=' .
                    $text .
                    '&as_user=true';
            file_get_contents($url);
        }
    }
}
