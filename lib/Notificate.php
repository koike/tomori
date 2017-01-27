<?php

class Notificate
{
    public static function slack(Analyze $tomori)
    {
        if($tomori->get_url() != null)
        {
            $token = getenv('SLACK_TOKEN');
            $channel = urlencode('#alert');
            $text = urlencode
                    (
                        '[Compromised (' . $tomori->get_description() . ')] ' . date('Y-m-d H:i:s') .
                        "\n```\n" .
                        $tomori->get_url() .
                        "\n```" .
                        'Gist => ' . $tomori->get_gist_url()
                    );
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
