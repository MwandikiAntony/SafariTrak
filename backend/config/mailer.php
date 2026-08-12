<?php

function st_smtp_read($socket): string {
    $response = '';
    while (!feof($socket)) {
        $line = fgets($socket, 516);
        if ($line === false) {
            break;
        }
        $response .= $line;
        if (preg_match('/^[0-9]{3}[^-]/', $line)) {
            break;
        }
    }
    return trim($response);
}

function st_smtp_code(string $response): int {
    return (int) substr(trim($response), 0, 3);
}

function st_smtp_send(string $smtpHost, int $smtpPort, string $smtpUser, string $smtpPass, string $fromEmail, string $fromName, string $toEmail, string $subject, string $body): bool {
    $transport = $smtpPort === 465 ? 'ssl' : 'tcp';
    $socket = stream_socket_client("{$transport}://{$smtpHost}:{$smtpPort}", $errno, $errstr, 20);
    if (!$socket) {
        return false;
    }

    stream_set_timeout($socket, 20);

    $read = fn() => st_smtp_read($socket);
    $write = fn(string $cmd) => fwrite($socket, $cmd);

    $banner = $read();
    if (st_smtp_code($banner) !== 220) {
        fclose($socket);
        return false;
    }

    $write("EHLO localhost\r\n");
    $ehlo = $read();
    if (st_smtp_code($ehlo) !== 250) {
        fclose($socket);
        return false;
    }

    $useStartTls = $transport !== 'ssl' && ($smtpPort === 587 || stripos($smtpHost, 'gmail') !== false);
    if ($useStartTls) {
        $write("STARTTLS\r\n");
        $startTls = $read();
        if (st_smtp_code($startTls) !== 220) {
            fclose($socket);
            return false;
        }

        if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            fclose($socket);
            return false;
        }

        $write("EHLO localhost\r\n");
        $secondEhlo = $read();
        if (st_smtp_code($secondEhlo) !== 250) {
            fclose($socket);
            return false;
        }
    }

    $write("AUTH LOGIN\r\n");
    $authRequest = $read();
    if (st_smtp_code($authRequest) !== 334) {
        fclose($socket);
        return false;
    }

    $write(base64_encode($smtpUser) . "\r\n");
    $userResponse = $read();
    if (st_smtp_code($userResponse) !== 334) {
        fclose($socket);
        return false;
    }

    $write(base64_encode($smtpPass) . "\r\n");
    $passResponse = $read();
    if (st_smtp_code($passResponse) !== 235) {
        fclose($socket);
        return false;
    }

    $envelopeFrom = $smtpUser ?: $fromEmail;
    $write("MAIL FROM:<{$envelopeFrom}>\r\n");
    $mailFromResponse = $read();
    if (st_smtp_code($mailFromResponse) !== 250) {
        fclose($socket);
        return false;
    }

    $write("RCPT TO:<{$toEmail}>\r\n");
    $rcptResponse = $read();
    $rcptCode = st_smtp_code($rcptResponse);
    if ($rcptCode !== 250 && $rcptCode !== 251) {
        fclose($socket);
        return false;
    }

    $write("DATA\r\n");
    $dataResponse = $read();
    if (st_smtp_code($dataResponse) !== 354) {
        fclose($socket);
        return false;
    }

    $headers = [];
    $headers[] = 'From: ' . $fromName . ' <' . $fromEmail . '>';
    $headers[] = 'To: ' . $toEmail;
    $headers[] = 'Subject: ' . $subject;
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: text/plain; charset=UTF-8';
    $headers[] = 'Content-Transfer-Encoding: 8bit';
    $headers[] = 'Date: ' . date('r');

    $messageBody = preg_replace('/^\./m', '..', $body);
    $message = implode("\r\n", $headers) . "\r\n\r\n" . $messageBody . "\r\n.\r\n";
    $write($message);
    $finalResponse = $read();
    if (st_smtp_code($finalResponse) !== 250) {
        fclose($socket);
        return false;
    }

    $write("QUIT\r\n");
    fclose($socket);
    return true;
}

function st_send_mail(string $to, string $subject, string $body, string $fromEmail = 'no-reply@safaritrak.local', string $fromName = 'SafariTrak'): bool {
    $smtpHost = getenv('SMTP_HOST') ?: getenv('GMAIL_SMTP_HOST') ?: 'smtp.gmail.com';
    $smtpPort = (int) (getenv('SMTP_PORT') ?: getenv('GMAIL_SMTP_PORT') ?: 587);
    $smtpUser = getenv('SMTP_USER') ?: getenv('GMAIL_SMTP_USER');
    $smtpPass = getenv('SMTP_PASS') ?: getenv('GMAIL_SMTP_PASS');

    if ($smtpUser && $smtpPass) {
        return st_smtp_send($smtpHost, $smtpPort, $smtpUser, $smtpPass, $fromEmail, $fromName, $to, $subject, $body);
    }

    return @mail($to, $subject, $body, "From: {$fromName} <{$fromEmail}>\r\n");
}
