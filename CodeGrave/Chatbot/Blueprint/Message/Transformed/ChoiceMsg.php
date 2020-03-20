<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Chatbot\Blueprint\Message\Transformed;

use Commune\Chatbot\Blueprint\Message\TransformedMsg;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface ChoiceMsg extends TransformedMsg
{
    public function index() : string;

    public function choice() : string;
}