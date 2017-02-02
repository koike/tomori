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
                            "[Gist]\n" . $tomori->get_gist_url()
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
            $channel = urlencode('#error');
            $text = urlencode
            (
                "[Error (" . $no . ")]\n" .
                "file:" . $file . " => line:" . $line . "\n" .
                "```\n" .
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
            $channel = urlencode('#exception');

            ob_start();
            var_dump($e);
            $exception_dump = ob_get_contents();
            ob_end_clean();

            $exception_dump = str_replace('```', '` ` `', $exception_dump);
            $code = $e->getCode();
            $file = $e->getFile();
            $line = $e->getLine();
            $trace = $e->getTraceAsString();
            $message = $e->getMessage();
            $text = urlencode
            (
                "[Exception (" . $code . ")]\n" .
                "file:" . $file . " => line:" . $line . "\n" .
                $message . "\n" .
                "```\n" .
                $trace .
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

    public static function shutdown()
    {
        $token = getenv('SLACK_TOKEN');
        if($token != null && $token != '')
        {
            $channel = urlencode('#alert');
            $text = urlencode
            (
                "[Alert]\nSystem abnormally terminated!\nRebooting System..."
            );

            $url = 'https://slack.com/api/chat.postMessage?token=' .
                    $token .
                    '&channel=' .
                    $channel .
                    '&text=' .
                    $text;
            file_get_contents($url);
        }

        // 無限に再起動するのを防ぐために一旦スリープする
        sleep(1 * 60);
        exec('nohup php tomori.php > /dev/null 2>&1 &', $arr, $res);
    }
}
