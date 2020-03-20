<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Framework\Blueprint\Chat;

use Commune\Shell\Blueprint\Reaction\Reaction;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface ReactionMessage
{
    public function getReaction() : Reaction;

    public function getChatId() : string;

    public function getShellId() : string;
}