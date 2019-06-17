<?php


namespace Commune\Chatbot\OOHost\Session;

use Commune\Chatbot\Blueprint\Conversation\Conversation;
use Commune\Chatbot\Blueprint\Message\Message;
use Commune\Chatbot\Config\ChatbotConfig;
use Commune\Chatbot\OOHost\Context\Context;
use Commune\Chatbot\OOHost\Context\ContextRegistrar;
use Commune\Chatbot\OOHost\Dialogue\Dialog;
use Commune\Chatbot\Blueprint\Conversation\IncomingMessage;
use Commune\Chatbot\OOHost\Context\Intent\IntentMessage;
use Commune\Chatbot\OOHost\Context\Intent\IntentRegistrar;
use Commune\Chatbot\OOHost\Directing\Navigator;
use Commune\Chatbot\Config\Host\OOHostConfig;
use Psr\Log\LoggerInterface;

/**
 *
 * @property-read string $sessionId
 * @property-read IncomingMessage $incomingMessage
 * @property-read Conversation $conversation
 * @property-read Scope $scope
 * @property-read LoggerInterface $logger
 *
 *
 * @property-read ContextRegistrar $contextRepo
 * @property-read IntentRegistrar $intentRepo
 *
 * @property-read Repository $repo
 * @property-read Dialog $dialog
 * @property-read ChatbotConfig $chatbotConfig
 * @property-read OOHostConfig $hostConfig
 * @property-read SessionMemory $memory
 */
interface Session
{

    /**
     * @return string[]
     */
    public static function getInstanceIds() : array;

    /*----- 响应会话 -----*/

    public function hear(Message $message, Navigator $navigator = null) : void;

    public function isHeard() : bool;

    public function isQuiting() : bool ;

    /*----- 设置当前的intent -----*/

    public function setMatchedIntent(IntentMessage $intent) : void;

    public function getMatchedIntent() : ? IntentMessage;


    /*----- 创建一个director -----*/

    public function newSession(string $belongsTo, \Closure $rootMaker) : Session;

    /*----- 保存必要的数据. -----*/

    public function makeRootContext() : Context;

    /*----- 保存必要的数据. -----*/

    /**
     * 不保存任何数据.
     */
    public function beSneak() : void;

    /**
     * 需要退出 session
     */
    public function shouldQuit() : void;

    public function finish() : void;
}