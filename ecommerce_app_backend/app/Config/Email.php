<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail;
    public string $fromName;
    public string $protocol = 'smtp'; // Set to SMTP
    public string $SMTPHost;
    public string $SMTPUser;
    public string $SMTPPass;
    public string $SMTPPort;
    public int $SMTPTimeout = 5;
    public bool $wordWrap   = true;
    public string $mailType = 'html'; // Set to HTML for polished invoice emails
    public string $charset  = 'utf-8';
    public bool $validate   = true;
    public string $CRLF     = "\r\n";
    public string $newline  = "\r\n";
    public string $SMTPCrypto = 'tls'; // Gmail uses TLS on port 587

    public function __class_construct()
    {
        // Automatically fetch values from your .env file
        $this->SMTPHost  = env('GMAIL_HOST', 'smtp.gmail.com');
        $this->SMTPPort  = (int) env('GMAIL_PORT', 587);
        $this->SMTPUser  = env('GMAIL_USERNAME');
        $this->SMTPPass  = env('GMAIL_PASSWORD');
        $this->fromEmail = env('GMAIL_FROM_EMAIL');
        $this->fromName  = env('GMAIL_FROM_NAME', 'Amazon-like Store');
    }
}
