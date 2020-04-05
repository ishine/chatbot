<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Ghost\Contracts;

use Commune\Framework\Blueprint\Intercom\GhostInput;
use Commune\Framework\Blueprint\Server\Request;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface GhtRequest extends Request
{

    /**
     * @return GhostInput
     */
    public function getGhostInput() : GhostInput;

    public function getSessionId() : string;
}