<?php

class Notificate
{
    public static function slack(Analyze $tomori)
    {
        if($tomori->get_url() != null)
        {
            $token = getenv('SLACK_TOKEN');
            if($token != null && $token != '')
            {
                $channel = urlencode('#alert');
                $text = urlencode
                        (
                            '[Compromised (' . $tomori->get_description() . ')] ' . date('Y-m-d H:i:s') .
                            "\n```\n" .
                            $tomori->get_url() .
                            "\n```\n\n" .
                            '[Gist]\n' . $tomori->get_gist_url()
                        );
                $url = 'https://slack.com/api/chat.postMessage?token=' .
                        $token .
                        '&channel=' .
                        $channel .
                        '&text=' .
                        $text;
                file_get_contents($url);
            }
        }
    }

    public static function error(\Exception $e)
    {
        ob_start();
        var_dump($e);
        $error_dump = ob_get_contents();
        ob_end_clean();

        $error_dump = str_replace('```', '` ` `', $error_dump);
        $message = $e->getMessage();
        $text = urlencode
        (
            "[Error]\n" .
            $message .
            "\n```\n" .
            $error_dump .
            "\n```"
        );

        $url = 'https://slack.com/api/chat.postMessage?token=' .
                $token .
                '&channel=' .
                $channel .
                '&text=' .
                $text;
        file_get_contents($url);
    }
}
