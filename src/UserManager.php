<?php

use Entity\User;

class UserManager
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function process($text)
    {
        $text = $this->replaceUserFirstName($text, $this->user);
        return $text;
    }

    public function replaceUserFirstName($text, $user)
    {
        if (strpos($text, '[user:first_name]')) {
            $text = str_replace('[user:first_name]', ucfirst(mb_strtolower($user->firstname)), $text);
        }
        return $text;
    }

}
