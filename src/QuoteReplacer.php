<?php

use Entity\Site;
use Entity\Quote;
use Entity\Destination;
use Repository\SiteRepository;
use Repository\DestinationRepository;

class QuoteReplacer implements ReplacerInterface
{
    /**
     * @var Quote $quote
     */
    private $quote;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    public function replaceProcess(string $text): string
    {
        $site = (new SiteRepository())->getById($this->quote->siteId);
        $destination = (new DestinationRepository())->getById($this->quote->destinationId);

        $text = $this->replaceQuoteSummaryHtml($text, $this->quote);
        $text = $this->replaceQuoteSummary($text, $this->quote);
        $text = $this->replaceQuoteDestinationName($text, $destination);
        $text = $this->replaceQuoteDestinationLink($text, $destination, $this->quote, $site);

        return $text;
    }

    public function replaceQuoteSummaryHtml(string $text, Quote $quote): string
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

    public function replaceQuoteSummary(string $text, Quote $quote): string
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

    public function replaceQuoteDestinationName(string $text, Destination $destination): string
    {
        if (strpos($text, '[quote:destination_name]')) {
            $text = str_replace('[quote:destination_name]', $destination->countryName, $text);
        }
        return $text;
    }

    public function replaceQuoteDestinationLink(string $text, Destination $destination, Quote $quote, Site $site): string
    {
        if (strpos($text, '[quote:destination_link]')) {
            $text = str_replace('[quote:destination_link]', $site->url . '/' . $destination->countryName . '/quote/' . $quote->id, $text);
        }
        return $text;
    }
}
