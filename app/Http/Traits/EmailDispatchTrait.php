<?php

namespace App\Http\Traits;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\Job; // Make sure this model is migrated
use Illuminate\Support\Facades\Log; // Use Laravel's Log facade
use Illuminate\Support\Facades\Config; // Use Laravel's Config facade

trait EmailDispatchTrait
{
    /**
     * Sends the email using PHPMailer.
     *
     * @param string $toEmail
     * @param string $toName
     * @param string $subject
     * @param string $htmlBody
     * @throws \Exception
     */
    public function sendActualEmail($toEmail, $toName, $subject, $htmlBody)
    {
        // Get email config from Laravel's .env file
        // Namma .env file-la irunthu settings edukkurom
        $smtp_host = Config::get('mail.mailers.smtp.host', env('MAIL_HOST'));
        $smtp_port = Config::get('mail.mailers.smtp.port', env('MAIL_PORT'));
        $smtp_username = Config::get('mail.mailers.smtp.username', env('MAIL_USERNAME'));
        $smtp_password = Config::get('mail.mailers.smtp.password', env('MAIL_PASSWORD'));
        $smtp_encryption = Config::get('mail.mailers.smtp.encryption', env('MAIL_ENCRYPTION', 'tls'));

        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host       = $smtp_host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtp_username;
            $mail->Password   = $smtp_password;
            $mail->SMTPSecure = ($smtp_encryption == 'tls') ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = $smtp_port;

            $mail->setFrom(env('MAIL_FROM_ADDRESS', 'no-reply@vortexfleet.com'), env('MAIL_FROM_NAME', 'VortexFleet'));
            $mail->addAddress($toEmail, $toName);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;

            $mail->send();
            
        } catch (Exception $e) {
            if (str_contains($e->getMessage(), 'SMTP Error: Could not authenticate')) {
                 throw new Exception('Email Server Error: Check Username/Password in .env file', $e->getCode(), $e);
            }
            throw new Exception("Email could not be sent. Mailer Error: {$mail->ErrorInfo}", $e->getCode(), $e);
        }
    }

    /**
     * Queues an email to be sent (or sends it immediately as a fallback).
     *
     * @param string $toEmail
     * @param string $toName
     * @param string $subject
     * @param string $htmlBody
     */
    protected function sendEmail($toEmail, $toName, $subject, $htmlBody)
    {
        $payload = json_encode([
            'toEmail' => $toEmail,
            'toName' => $toName,
            'subject' => $subject,
            'htmlBody' => $htmlBody
        ]);

        try {
            // Namma database queue table-la save panrom
            Job::create([
                'queue' => 'email',
                'payload' => $payload,
                'available_at' => time(),
                'created_at' => time()
            ]);
        } catch (\Exception $e) {
            Log::critical("Could not dispatch email job to queue! Error: " . $e->getMessage(), [
                'email' => $toEmail,
                'subject' => $subject
            ]);
            Log::warning("Falling back to synchronous email sending for " . $toEmail);
            
            // Fallback: Ippove email anuppidu
            $this->sendActualEmail($toEmail, $toName, $subject, $htmlBody);
        }
    }
}