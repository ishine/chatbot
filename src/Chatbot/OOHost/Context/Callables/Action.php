<?php


namespace Commune\Chatbot\OOHost\Context\Callables;


use Commune\Chatbot\Blueprint\Message\Message;
use Commune\Chatbot\OOHost\Context\Context;
use Commune\Chatbot\OOHost\Dialogue\Dialog;
use Commune\Chatbot\OOHost\Directing\Navigator;

interface Action
{

    public function __invoke(
        Context $self,
        Dialog $dialog,
        Message $message
    ) : ? Navigator;

}