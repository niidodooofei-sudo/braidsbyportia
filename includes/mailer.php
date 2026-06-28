<?php
function send_email(string $to, string $subject, string $body): void {
    $api_key = getenv('RESEND_API_KEY');
    if (!$api_key) {
        error_log("mailer: no RESEND_API_KEY set — skipping email to $to");
        return;
    }
    $from = getenv('MAIL_FROM') ?: 'Braids by Portia <noreply@braidsbyportia.com>';
    $ch = curl_init('https://api.resend.com/emails');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode(['from' => $from, 'to' => [$to], 'subject' => $subject, 'text' => $body]),
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $api_key,
            'Content-Type: application/json',
        ],
    ]);
    curl_exec($ch);
    curl_close($ch);
}
