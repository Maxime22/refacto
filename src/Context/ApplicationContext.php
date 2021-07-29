<?php

namespace Context;
use Entity\Site;
use Entity\User;

class ApplicationContext
{
    /**
     * @var Site
     */
    private $currentSite;
    /**
     * @var User
     */
    private $currentUser;

    public function __construct()
    {
        $faker = \Faker\Factory::create();
        $this->currentSite = new Site($faker->randomNumber(), $faker->url);
        $this->currentUser = new User($faker->randomNumber(), $faker->firstName, $faker->lastName, $faker->email);
    }

    public function getCurrentSite(): Site
    {
        return $this->currentSite;
    }

    public function getCurrentUser(): User
    {
        return $this->currentUser;
    }
}
