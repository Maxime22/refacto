<?php

namespace Entity;

class User
{
    /**
     * @var int $id
     */
    public $id;
    /**
     * @var string $firstname
     */
    public $firstname;
    /**
     * @var string $lastname
     */
    public $lastname;
    /**
     * @var string $email
     */
    public $email;

    public function __construct(int $id, string $firstname, string $lastname, string $email)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
    }
}
