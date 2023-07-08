<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    public function __construct(private MailerInterface $mailer)
    {
    }
    public function sendEmail(
        $to,
        $content,
        $subject,
        $attachmentPath,
        $attachmentFileName
    ): void {
        $email = (new Email())
            ->from('imed.ay95@gmail.com')
            ->to($to)
            ->subject($subject)
            ->html($content);

        $email->attachFromPath($attachmentPath, $attachmentFileName);


        $this->mailer->send($email);
    }
}
