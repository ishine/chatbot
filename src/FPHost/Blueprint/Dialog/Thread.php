<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\FPHost\Blueprint\Dialog;
use Commune\Chatbot\Blueprint\Message\ReplyMsg;


/**
 * 有依赖关系的对话线程
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface Thread
{
    public function id() : string;

    public function currentStage() : Stage;

    public function intended() : ? Stage;

    /*------ status ------*/


    /*------ interactions ------*/

    # 当前对话与用户的互动信息. 也用于匹配, 对话状态查询等.

    /**
     * 当前对用户询问的信息.
     * @return ReplyMsg|null
     */
    public function query() : ? ReplyMsg;

    /**
     * 对用户进行的主动提示信息. index => suggestion
     * @return array
     */
    public function suggestions() : array;

    /**
     * @return array
     */
    public function choices() : array;

    /**
     * @return array
     */
    public function hearingCommands() : array;

    public function hearingIntents() : array;

    /**
     * 允许的开放域意图
     * @return string[]
     */
    public function allowIntents() : array;

}