<?php

namespace App\Notification;

use App\Entity\Contact;
use Twig\Environment;

class ContactNotification {

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $renderer;


    public function __construct( \Swift_Mailer $mailer, Environment $renderer )
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer; 
    }

    public function notify(Contact $contact){
        $message = (new \Swift_Message ($contact->getSujet()))
            ->setFrom('nakzorh@gmail.com')
            ->setTo($contact->getMail())
            ->setBody($this->renderer->render('email/contact.html.twig', [
                'contact' => $contact
            ]), 
            'text/html');
        
        return $this->mailer->send($message);
    }
}