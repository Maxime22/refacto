<?php

use Entity\Template;
use Context\ApplicationContext;
use Repository\SiteRepository;
use Repository\DestinationRepository;
use Entity\Quote;
use Entity\User;

class TemplateManager
{
    private $applicationContext;

    public function __construct(ApplicationContext $applicationContext)
    {
        $this->applicationContext = $applicationContext;
    }

    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone ($tpl);
        $replaced->subject = $this->computeText($replaced->subject, $data);
        $replaced->content = $this->computeText($replaced->content, $data);

        return $replaced;
    }


    protected function computeText($text, array $data)
    {
        /*
         * QUOTE
         * [quote:*]
         */
        $quote = $this->checkIfQuoteInData($data);

        if ($quote) {
            $site = (new SiteRepository())->getById($quote->siteId);
            $destination = (new DestinationRepository())->getById($quote->destinationId);

            $text = $this->replaceQuoteSummaryHtml($text, $quote);
            $text = $this->replaceQuoteSummary($text, $quote);
            $text = $this->replaceQuoteDestinationName($text, $destination);
            $text = $this->replaceQuoteDestinationLink($text, $destination, $quote, $site);
        }

        /*
         * USER
         * [user:*]
         */
        $user  = $this->checkIfUserInData($data);
        if ($user) {
            $text = $this->replaceUserFirstName($text, $user);
        }

        return $text;
    }

    public function checkIfQuoteInData($data)
    {
        return (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : null;
    }

    public function checkIfUserInData($data)
    {
        return (isset($data['user'])  and ($data['user']  instanceof User))  ? $data['user']  : $this->applicationContext->getCurrentUser();
    }

    public function replaceQuoteSummaryHtml($text, $quote)
    {
        if (strpos($text, '[quote:summary_html]')) {
            $text = str_replace(
                '[quote:summary_html]',
                Quote::renderHtml($quote),
                $text
            );
        }
        return $text;
    }

    public function replaceQuoteSummary($text, $quote)
    {
        if (strpos($text, '[quote:summary]')) {
            $text = str_replace(
                '[quote:summary]',
                Quote::renderText($quote),
                $text
            );
        }
        return $text;
    }

    public function replaceQuoteDestinationName($text, $destination)
    {
        if (strpos($text, '[quote:destination_name]')) {
            $text = str_replace('[quote:destination_name]', $destination->countryName, $text);
        }
        return $text;
    }

    public function replaceQuoteDestinationLink($text, $destination, $quote, $site)
    {
        if (strpos($text, '[quote:destination_link]')) {
            $text = isset($destination) ? str_replace('[quote:destination_link]', $site->url . '/' . $destination->countryName . '/quote/' . $quote->id, $text) : str_replace('[quote:destination_link]', '', $text);
        }
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
