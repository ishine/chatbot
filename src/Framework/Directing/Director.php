<?php

/**
 * Class Director
 * @package Commune\Chatbot\Host\Direction
 */

namespace Commune\Chatbot\Framework\Directing;

use Commune\Chatbot\Contracts\ChatbotApp;
use Commune\Chatbot\Framework\Exceptions\ConfigureException;
use Commune\Chatbot\Framework\Context\Context;
use Commune\Chatbot\Framework\Context\ContextCfg;
use Commune\Chatbot\Framework\Conversation\Conversation;
use Commune\Chatbot\Framework\Conversation\Scope;
use Commune\Chatbot\Framework\Routing\Router;
use Commune\Chatbot\Framework\Session\Session;
use Commune\Chatbot\Framework\Intent\IntentData;
use Psr\Log\LoggerInterface;

class Director
{
    /**
     * @var ChatbotApp
     */
    protected $app;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var History
     */
    protected $history;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var LoggerInterface
     */
    protected $log;

    /**
     * @var int
     */
    protected $tick;

    /**
     * @var int
     */
    protected $maxTicks;

    /**
     * @var Scope
     */
    protected $scope;

    /**
     * @var Conversation
     */
    protected $conversation;

    /**
     * Director constructor.
     * @param ChatbotApp $app
     * @param Session $session
     * @param Router $router
     */
    public function __construct(ChatbotApp $app, Session $session, Router $router)
    {
        $this->app = $app;
        $this->session = $session;
        $this->history = $this->session->getHistory();
        $this->conversation = $this->session->getConversation();
        $this->router = $router;
        $this->log = $app->make(LoggerInterface::class);
        $this->tick = 0;
        $this->maxTicks = $app->getDirectorMaxTicks();
        $this->scope = $this->session->getConversation()->getScope();
    }

    public function makeLocation(string $contextName, array $props)  : Location
    {
        $id = $this->router->getContextId($contextName, $this->scope);
        return new Location($contextName, $props, $id);
    }

    public function dispatch() : Conversation
    {
        try {
            $current = $this->history->current();
            $this->debug($current);

            $context = $this->fetchContext($current);
            $context->fireEvent(ContextCfg::WAKED_HOOK);
            $dialogRoute = $this->router->getDialogRoute($context->getName());
            $intentRoute = $dialogRoute->match($context, $this->conversation);
            return $intentRoute->run($context, $this->conversation, $this);

        } catch (TooManyDirectingException $e) {
            $this->history->flush();
            throw $e;
        } catch (\Exception $e) {
            $this->log->error($e->getMessage());
            //todo
            return $this->failed();
        }
    }

    public function cancel() : Conversation
    {
        $this->ticks();
        $current = $this->history->current();
        $last = $current;
        while($intended = $last->getIntended()) {
            $last = $intended;
        }
        $context = $this->fetchContext($last);
        $context->fireEvent(ContextCfg::CANCELED_HOOK);
        return $this->backward();
    }

    public function failed() : Conversation
    {
        $this->ticks();
        $current = $this->history->current();
        $last = $current;
        while($intended = $last->getIntended()) {
            $last = $intended;
        }
        $context = $this->fetchContext($last);
        $context->fireEvent(ContextCfg::FAILED_HOOK);
        return $this->backward();
    }

    public function home() : Conversation
    {
        $this->ticks();
        $location = $this->history->home();
        return $this->startDialog($location);
    }

    public function to(Location $location) : Conversation
    {
        $this->ticks();
        $this->history->to($location);
        return $this->startDialog($location);
    }

    public function forward() : Conversation
    {
        $this->ticks();
        $location = $this->history->forward();
        $context = $this->fetchContext($location);
        $context->fireEvent(ContextCfg::RESTORED_HOOK);
        return $this->conversation;
    }

    public function backward() : Conversation
    {
        $this->ticks();
        $location = $this->history->backward();
        $context = $this->fetchContext($location);
        $context->fireEvent(ContextCfg::RESTORED_HOOK);
        return $this->conversation;
    }

    public function guest(Location $to, Location $from = null, string $callback = null) : Conversation
    {
        $this->ticks();

        if (!$from) {
            $from = $this->history->current();
        }
        $from->setCallback($callback);
        $to->setIntended($from);
        $this->history->setCurrent($to);
        return $this->startDialog($to);
    }

    public function intended() : Conversation
    {
        $this->ticks();
        $current = $this->history->current();
        $intended = $current->getIntended();
        if (!isset($intended)) {
            return $this->backward();
        }
        $this->history->setCurrent($intended);
        $callback = $intended->getCallback();

        if (!isset($callback)) {
            return $this->startDialog($intended);
        }

        $intendedContext = $this->fetchContext($intended);
        $intendedDialogRoute = $this->router->getDialogRoute($intendedContext->getName());
        $callbackRoute = $intendedDialogRoute->getIntentRoute($callback);

        if (empty($callbackRoute)) {
            throw new ConfigureException();
        }

        $callbackIntent = new IntentData(
            $this->conversation->getMessage(),
            $this->fetchContext($current), // 将回调对象当成入参
            $current->getContextName()
        );
        $this->conversation->setMatchedIntent($callbackIntent);
        return $callbackRoute->run($intendedContext, $this->conversation, $this);
    }


    /*--------- status ----------*/

    protected function startDialog(Location $location) : Conversation
    {
        $context = $this->fetchContext($location);

        $context->fireEvent(ContextCfg::CREATED_HOOK);

        if ($dependency = $context->depending()) {
            return $this->guest($dependency, $location);
        }
        $dialogRoute = $this->router->getDialogRoute($context->getName());
        $preparedRoute = $dialogRoute->prepared();
        return $preparedRoute->run($context, $this->conversation, $this);
    }


    protected function fetchContext(Location $location) : Context
    {
        $context = $this->session->fetchContextByLocation($location);
        $locationId = $location->getContextId();
        if (!isset($locationId)) {
            $location->setContextId($context->getId());
        }
        return $context;
    }

    protected function ticks()
    {
        //var_dump(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]);
        $this->tick++;

        if ($this->tick > $this->maxTicks) {
            //todo
            $this->log->error($message = 'too match redirection : '.$this->tick);
            throw new TooManyDirectingException($message);
        }
    }

    protected function debug(Location $location)
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $func = $backtrace[1]['function'];
        $this->log->debug('Host Director run '.$func.' with '.$location);
    }
}