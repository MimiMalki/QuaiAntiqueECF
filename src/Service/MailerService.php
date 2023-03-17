<?php

namespace App\Service;


use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    public function __construct(private MailerInterface $mailer){}

    public function sendEmail(
        $to='meriamemalki96@gmail.com',
        $content='<p>This is the HTML version</p>',
        $subject='This e-mail is for testing purpose',
        $text='This is the text version'
    ): void
    {
        $email = (new Email())
        ->from('quaiantique@gmail.com')
        ->to($to)
        ->subject($subject)
        ->text($text)
        ->html($content);
    
        $this->mailer->send($email);

        
    }
}