<?php

namespace Entity;

class Quote
{
    /**
     * @var int $id
     */
    public $id;
    /**
     * @var int $siteId
     */
    public $siteId;
    /**
     * @var int $destinationId
     */
    public $destinationId;
     /**
     * @var string $dateQuoted
     */
    public $dateQuoted;

    public function __construct(int $id,int $siteId,int $destinationId,string $dateQuoted)
    {
        $this->id = $id;
        $this->siteId = $siteId;
        $this->destinationId = $destinationId;
        $this->dateQuoted = $dateQuoted;
    }

    public static function renderHtml(Quote $quote): string
    {
        return '<p>' . $quote->id . '</p>';
    }

    public static function renderText(Quote $quote): string
    {
        return (string) $quote->id;
    }

    public function getDestinationId(): int{
        return $this->destinationId;
    }
}