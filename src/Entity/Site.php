<?php

namespace Entity;

class Site
{
    /**
     * @var int $id
     */
    public $id;
    /**
     * @var string $url
     */
    public $url;

    public function __construct(int $id, string $url)
    {
        $this->id = $id;
        $this->url = $url;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
