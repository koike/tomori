<?php

class Notificate
{
    public static function slack($tomori = null)
    {
        $token = getenv('SLACK_TOKEN');
        $channel = urlencode('#alert');
        $text = urlencode('[Compromised] ' . date('Y-m-d H:i:s') . '\n' . '```' . $tomori->url . '```');
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
