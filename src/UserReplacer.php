<?php

use Entity\User;

class UserReplacer implements ReplacerInterface
{
    /**
     * @var User $user
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function replaceProcess(string $text): string
    {
        $text = $this->replaceUserFirstName($text, $this->user);
        return $text;
    }

    public function replaceUserFirstName(string $text,User $user): string
    {
        if (strpos($text, '[user:first_name]')) {
            $text = str_replace('[user:first_name]', ucfirst(mb_strtolower($user->firstname)), $text);
        }
        return $text;
    }

}
