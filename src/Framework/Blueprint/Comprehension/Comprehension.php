<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Framework\Blueprint\Comprehension;


/**
 * 对 IncomingMessage 的理解结果
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface Comprehension
{
    public function getIntentMessage() : IntentMsg

    public function nlu() : NLU;

    public function cmd() :

}