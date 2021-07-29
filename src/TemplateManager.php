<?php

use Entity\User;
use Entity\Quote;
use Entity\Template;
use Context\ApplicationContext;

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
            $quoteManager = new QuoteManager($quote);
            $text = $quoteManager->process($text);
        }

        /*
         * USER
         * [user:*]
         */
        $user  = $this->checkIfUserInData($data);
        if ($user) {
            $userManager = new UserManager($user);
            $text = $userManager->process($text);
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
}
