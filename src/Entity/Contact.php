<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact {

    /**
     * @var string
     * @Assert\NotBlank
     */
    private $lastname;

    /**
     * @var string
     * @Assert\NotBlank
     */
    private $firstname;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $mail;

    /**
     * @var string
     * @Assert\NotBlank
     * 
     */
    private $sujet;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(min=10)
     */
    private $message;

    /**
     * Get the value of lastname
     *
     * @return  string
     */ 
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @param  string  $lastname
     *
     * @return  self
     */ 
    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of firstname
     *
     * @return  string
     */ 
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @param  string  $firstname
     *
     * @return  self
     */ 
    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of mail
     *
     * @return  string
     */ 
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set the value of mail
     *
     * @param  string  $mail
     *
     * @return  self
     */ 
    public function setMail(string $mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get the value of sujet
     *
     * @return  string
     */ 
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * Set the value of sujet
     *
     * @param  string  $sujet
     *
     * @return  self
     */ 
    public function setSujet(string $sujet)
    {
        $this->sujet = $sujet;

        return $this;
    }

    /**
     * Get the value of message
     *
     * @return  string
     */ 
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @param  string  $message
     *
     * @return  self
     */ 
    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }
}