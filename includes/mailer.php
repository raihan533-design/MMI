<?php
/**
 * Minimal mail helper built on PHP's mail().
 * For reliable delivery on shared hosting, consider swapping this for SMTP (PHPMailer).
 */
function sendMail($to, $subject, $body, $replyTo = '')
{
    $clean = function ($v) {
        return trim(str_replace(["\r", "\n", "%0a", "%0d"], ' ', (string)$v));
    };

    $host = preg_replace('/[^a-z0-9.\-]/i', '', $_SERVER['HTTP_HOST'] ?? 'localhost');
    $from = 'noreply@' . ($host !== '' ? $host : 'localhost');

    $headers = [];
    $headers[] = 'From: ' . $clean(getSetting('company_name', 'kiuixshop')) . ' <' . $from . '>';
    if ($replyTo !== '' && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
        $headers[] = 'Reply-To: ' . $clean($replyTo);
    }
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: text/plain; charset=UTF-8';

    return @mail($clean($to), $clean($subject), $body, implode("\r\n", $headers));
}
