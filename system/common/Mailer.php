<?php

/**
 * This file is part of the dashboard.rgbvision.net package.
 *
 * (c) Alex Graham <contact@rgbvision.net>
 *
 * @package    dashboard.rgbvision.net
 * @author     Alex Graham <contact@rgbvision.net>
 * @copyright  Copyright 2017-2021, Alex Graham
 * @license    https://dashboard.rgbvision.net/license.txt MIT License
 * @version    2.11
 * @link       https://dashboard.rgbvision.net
 * @since      File available since Release 2.0
 */

class Mailer
{

    private static function str_nospace(string $string): string
    {
        return trim(str_replace([' ', "\r", "\n", "\t"], '', $string));
    }

    /**
     * Get mailer settings
     *
     * @param string $field parameter key or all settings if empty
     * @return mixed
     */
    private static function getSettings(string $field = '')
    {

        static $settings = null;

        if ($settings === null) {
            $_settings = DB::query("SELECT * FROM settings");
            foreach ($_settings as $settings_item) {
                $settings[$settings_item['key']] = $settings_item['value'];
            }
        }

        if ($field === '') {
            return $settings;
        }

        return $settings[$field] ?? null;
    }

    /**
     * Send Email
     *
     * @param mixed $to recipient email address
     * @param string $body email body / message
     * @param string $subject message subject
     * @param string $from_email sender email
     * @param string $from_name sender name
     * @param string $type message type (html or plain text)
     * @param array $attach attachments paths
     * @param bool $saveattach should save attachments to ATTACH_DIR
     * @param bool $signature add signature
     * @return bool
     * @throws Exception
     */
    public static function send($to = '', $body = '', $subject = '', $from_email = '', $from_name = '', $type = 'text', $attach = [], $saveattach = false, $signature = true): bool
    {

        Loader::addDirectory(DASHBOARD_DIR . '/libraries/mailer');
        require_once DASHBOARD_DIR . '/libraries/SwiftMailer/swift_required.php';

        unset($transport, $message, $mailer);

        if (is_array($to)) {
            foreach ($to as &$item) {
                $item = self::str_nospace($item);
            }
            unset($item);
        } else {
            $to = self::str_nospace($to);
            $to = str_replace(';', ',', $to);
            if (strpos($to, ',') !== false) {
                $to = explode(',', $to);
            }
        }

        $from_email = empty($from_email) ? self::getSettings('from_email') : self::str_nospace($from_email);
        $from_name = empty($from_name) ? self::getSettings('from_name') : $from_name;

        // message type
        $type = ((strtolower($type) === 'html' || strtolower($type) === 'text/html') ? 'text/html' : 'text/plain');

        // add signature
        // ToDo: optimize and reduce
        if ($signature) {
            if ($type === 'text/html') {
                $signature = '<br><br>--<br>' . nl2br(self::getSettings('signature'));
                // generate message body
                $body = (strpos($body, '</body>') === false)
                    ? stripslashes($body) . $signature
                    : str_replace('</body>', $signature . '</body>', stripslashes($body));
            } else {
                $signature = "\r\n\r\n--\r\n" . self::getSettings('signature');
                // generate message body
                $body = stripslashes($body) . $signature;
            }
        } else {
            // generate message body
            $body = stripslashes($body);
        }

        if ($type === 'text/html') {
            $body = str_replace(["\t", "\r", "\n", '  ', '> <'], ['', '', '', ' ', '><'], $body);
        }

        $message = new Swift_Message($subject);
        $message->setFrom([$from_email => $from_name])
            ->setTo($to)
            ->setContentType($type)
            ->setBody($body)
            ->setMaxLineLength(800);

        // attachments
        if ($attach) {
            foreach ($attach as $attach_file) {
                $message->attach(Swift_Attachment::fromPath(trim($attach_file)));
            }
        }

        // generate transport
        // ToDo: remove deprecated transport
        switch (self::getSettings('mail_type')) {
            default:
            case 'mail':
                $transport = new Swift_MailTransport();
                break;

            case 'smtp':
                $transport = new Swift_SmtpTransport(stripslashes(self::getSettings('smtp_host')), (int)self::getSettings('smtp_port'));

                $smtp_encrypt = self::getSettings('smtp_encrypt');
                if ($smtp_encrypt)
                    $transport
                        ->setEncryption(strtolower(stripslashes($smtp_encrypt)));

                $smtp_user = self::getSettings('smtp_login');
                $smtp_pass = self::getSettings('smtp_pass');
                if ($smtp_user)
                    $transport
                        ->setUsername(stripslashes($smtp_user))
                        ->setPassword(stripslashes($smtp_pass));
                break;

            case 'sendmail':
                $transport = new Swift_SendmailTransport(self::getSettings('sendmail_path'));
                break;
        }

        // saving attachments
        if ($attach && $saveattach) {
            $attach_dir = DASHBOARD_DIR . '/' . ATTACH_DIR . '/';
            foreach ($attach as $file_path) {
                if ($file_path && file_exists($file_path)) {
                    $file_name = basename($file_path);
                    $file_name = str_replace(' ', '', mb_strtolower(trim($file_name)));
                    if (file_exists($attach_dir . $file_name)) {
                        $file_name = random_int(1000, 9999) . '_' . $file_name;
                    }
                    $file_path_new = $attach_dir . $file_name;
                    if (!@move_uploaded_file($file_path, $file_path_new)) {
                        copy($file_path, $file_path_new);
                    }
                }
            }
        }

        $mailer = new Swift_Mailer($transport);

        if (!@$mailer->send($message, $failures)) {
            return false;
        }

        return true;

    }

}
