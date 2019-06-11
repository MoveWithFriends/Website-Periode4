<?php


namespace App;

use App\Config;
use Mailgun\Mailgun;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

Class mail
{
    public static function send($to, $subject, $text, $html)
    {
# Instantiate the client.
        $mg = Mailgun::create(Config::MAILGUN_API_KEY);
        $mg->messages()->send(Config::MAILGUN_DOMAIN, [
            'from' => 'MoveWithFriends <joostoomen@hotmail.com>',
            'to' => $to,
            'subject' => $subject,
            'text' => $text,
            'html' => $html
        ]);
    }
}
