<?php

use Entity\Quote;
use Repository\SiteRepository;
use Repository\DestinationRepository;

class QuoteManager
{

    private $quote;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    public function process($text)
    {
        $site = (new SiteRepository())->getById($this->quote->siteId);
        $destination = (new DestinationRepository())->getById($this->quote->destinationId);

        $text = $this->replaceQuoteSummaryHtml($text, $this->quote);
        $text = $this->replaceQuoteSummary($text, $this->quote);
        $text = $this->replaceQuoteDestinationName($text, $destination);
        $text = $this->replaceQuoteDestinationLink($text, $destination, $this->quote, $site);

        return $text;
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
}
