<?php

use Entity\User;
use Entity\Quote;
use Entity\Template;
use Context\ApplicationContext;

class TemplateManager
{
    /**
     * @var ApplicationContext $applicationContext
     */
    private $applicationContext;

    public function __construct(ApplicationContext $applicationContext)
    {
        $this->applicationContext = $applicationContext;
    }

    /**
     * @param array<mixed> $data
     */
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

    /**
     * @param array<mixed> $data
     */
    protected function computeText(string $text, array $data): string
    {
        /*
         * QUOTE
         * [quote:*]
         */
        $quote = $this->checkIfQuoteInData($data);
        if ($quote) {
            $quoteManager = new QuoteManager($quote);
            $text = $quoteManager->replaceProcess($text);
        }

        /*
         * USER
         * [user:*]
         */
        $user  = $this->checkIfUserInData($data);
        if ($user) {
            $userManager = new UserManager($user);
            $text = $userManager->replaceProcess($text);
        }

        return $text;
    }

    /**
     * @param array<mixed> $data
     */
    public function checkIfQuoteInData(array $data)
    {
        return (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : null;
    }

    /**
     * @param array<mixed> $data
     */
    public function checkIfUserInData(array $data)
    {
        return (isset($data['user'])  and ($data['user']  instanceof User))  ? $data['user']  : $this->applicationContext->getCurrentUser();
    }
}
