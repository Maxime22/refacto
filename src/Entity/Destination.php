<?php

namespace Entity;

class Destination
{
    /**
     * @var int $id
     */
    public $id;
    /**
     * @var string $countryName
     */
    public $countryName;
    /**
     * @var string $conjunction
     */
    public $conjunction;
    /**
     * @var string $name
     */
    public $name;
    /**
     * @var string $computerName
     */
    public $computerName;

    public function __construct(int $id, string $countryName, string $conjunction, string $computerName)
    {
        $this->id = $id;
        $this->countryName = $countryName;
        $this->conjunction = $conjunction;
        $this->computerName = $computerName;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
