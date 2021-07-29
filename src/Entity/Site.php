<?php

namespace Entity;

class Site
{
    public $id;
    public $url;

    public function __construct($id, $url)
    {
        $this->id = $id;
        $this->url = $url;
    }

    public function getId(): int{
        return $this->id;
    }
}
