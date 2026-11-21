<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class MailConfigService
{
    /**
     * Apply SMTP / Mail configurations dynamically from the database.
     */
    public static function apply(): void
    {
        try {
            if (!Schema::hasTable('settings')) {
                return;
            }

            $mailer      = Setting::get('mail_mailer');
            $host        = Setting::get('mail_host');
            $port        = Setting::get('mail_port');
            $username    = Setting::get('mail_username');
            $password    = Setting::get('mail_password');
            $encryption  = Setting::get('mail_encryption');
            $fromAddress = Setting::get('mail_from_address');
            $fromName    = Setting::get('mail_from_name');

            // 1. Default Mailer
            if (!empty($mailer)) {
                Config::set('mail.default', trim($mailer));
            }

            // 2. SMTP Host
            if (!empty($host)) {
                Config::set('mail.mailers.smtp.host', trim($host));
            }

            // 3. SMTP Port
            if (!empty($port)) {
                Config::set('mail.mailers.smtp.port', (int) trim($port));
            }

            // 4. SMTP Credentials
            if ($username !== null && $username !== '') {
                Config::set('mail.mailers.smtp.username', trim($username));
            }

            if ($password !== null && $password !== '') {
                Config::set('mail.mailers.smtp.password', trim($password));
            }

            // 5. Scheme & Encryption
            if ($encryption !== null && $encryption !== '') {
                $encValue = in_array(strtolower(trim($encryption)), ['none', 'null', '']) ? null : trim($encryption);
                Config::set('mail.mailers.smtp.encryption', $encValue);
                Config::set('mail.mailers.smtp.scheme', $encValue);
            }

            // 6. Global From Address & Name
            if (!empty($fromAddress)) {
                Config::set('mail.from.address', trim($fromAddress));
            }

            if (!empty($fromName)) {
                Config::set('mail.from.name', trim($fromName));
            }

            // Purge cached mailers so MailManager applies runtime credentials
            Mail::purge();
        } catch (\Throwable $e) {
            // Silently fallback if DB is not ready during early bootstrap
        }
    }
}
