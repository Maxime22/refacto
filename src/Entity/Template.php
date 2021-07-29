<?php

namespace Entity;

class Template
{
    /**
     * @var int $id
     */
    public $id;
    /**
     * @var string $subject
     */
    public $subject;
    /**
     * @var string $content
     */
    public $content;

    public function __construct(int $id, string $subject, string $content)
    {
        $this->id = $id;
        $this->subject = $subject;
        $this->content = $content;
    }
}
