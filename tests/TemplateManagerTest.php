<?php

namespace Tests;

use Entity\User;
use Entity\Quote;
use Entity\Template;
use TemplateManager;
use Repository\SiteRepository;
use Context\ApplicationContext;
use Repository\DestinationRepository;

class TemplateManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Init the mocks
     */
    public function setUp()
    {
        $this->faker = \Faker\Factory::create();
        $this->applicationContext = new ApplicationContext();
        $this->quote = new Quote($this->faker->randomNumber(), $this->faker->randomNumber(), $this->faker->randomNumber(), $this->faker->date());
        $this->currentUser = $this->applicationContext->getCurrentUser();
        $this->templateManager = new TemplateManager($this->applicationContext);
    }

    /**
     * Closes the mocks
     */
    public function tearDown()
    {
    }

    /**
     * @test
     */
    public function test()
    {
        $expectedUser = $this->currentUser;
        $expectedDestination = (new DestinationRepository)->getById($this->quote->getDestinationId());

        $template = new Template(
            1,
            'Votre voyage avec une agence locale [quote:destination_name]',
            "
Bonjour [user:first_name],

Merci d'avoir contacté un agent local pour votre voyage [quote:destination_name].

Bien cordialement,

L'équipe Evaneos.com
www.evaneos.com
"
        );

        $message = $this->templateManager->getTemplateComputed(
            $template,
            [
                'quote' => $this->quote
            ]
        );

        $this->assertEquals('Votre voyage avec une agence locale ' . $expectedDestination->countryName, $message->subject);
        $this->assertEquals("
Bonjour " . $expectedUser->firstname . ",

Merci d'avoir contacté un agent local pour votre voyage " . $expectedDestination->countryName . ".

Bien cordialement,

L'équipe Evaneos.com
www.evaneos.com
", $message->content);
    }

    public function testWithUserInData()
    {
        $expectedUser = new User(1, 'Jean', 'Dupond', 'demo@hotmail.fr');
        $expectedDestination = (new DestinationRepository)->getById($this->quote->getDestinationId());

        // First string is subject
        // Second string is content
        $template = new Template(
            1,
            ' [quote:destination_name]',
            ' [user:first_name][quote:destination_name]'
        );
        
        $message = $this->templateManager->getTemplateComputed(
            $template,
            [
                'quote' => $this->quote,
                'user' => $expectedUser
            ]
        );
        $this->assertEquals(" ".$expectedDestination->countryName, $message->subject);
        $this->assertEquals(" ".$expectedUser->firstname . $expectedDestination->countryName, $message->content);
    }

    public function testWithLinkInQuote()
    {
        $expectedLink = (new SiteRepository())->getById($this->quote->siteId);
        $expectedDestination = (new DestinationRepository)->getById($this->quote->getDestinationId());

        // First string is subject
        // Second string is content
        $template = new Template(
            1,
            ' [quote:destination_link]',
            ' [quote:destination_link]'
        );
        
        $message = $this->templateManager->getTemplateComputed(
            $template,
            [
                'quote' => $this->quote
            ]
        );
        $this->assertEquals(" ".$expectedLink->url.'/'.$expectedDestination->countryName.'/quote/' . $this->quote->id, $message->subject);
        $this->assertEquals(" ".$expectedLink->url.'/'.$expectedDestination->countryName.'/quote/' . $this->quote->id, $message->content);
    }

    public function testWithSummaryAndSummaryHtmlInQuote()
    {
        // First string is subject
        // Second string is content
        $template = new Template(
            1,
            ' [quote:summary]',
            ' [quote:summary][quote:summary_html]'
        );
        
        $message = $this->templateManager->getTemplateComputed(
            $template,
            [
                'quote' => $this->quote
            ]
        );
        $this->assertEquals(" ". $this->quote->id, $message->subject);
        $this->assertEquals(" ". $this->quote->id.'<p>' . $this->quote->id . '</p>', $message->content);
    }
}
