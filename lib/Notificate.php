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

    public static function error($no, $str, $file, $line)
    {
        $token = getenv('SLACK_TOKEN');
        if($token != null && $token != '')
        {
            $channel = urlencode('#alert');
            $text = urlencode
            (
                "[Error (" .
                $no .
                ")]\nfile:" .
                $file .
                " => line:" .
                $line .
                "\n```\n" .
                $str .
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

    public static function exception(\Exception $e)
    {
        $token = getenv('SLACK_TOKEN');
        if($token != null && $token != '')
        {
            ob_start();
            var_dump($e);
            $exception_dump = ob_get_contents();
            ob_end_clean();

            $exception_dump = str_replace('```', '` ` `', $exception_dump);
            $message = $e->getMessage();
            $text = urlencode
            (
                "[Exception]\n" .
                $message .
                "\n```\n" .
                $exception_dump .
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
}
