<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Blueprint\Ghost\MindMeta;

use Commune\Support\Option\AbsMeta;
use Commune\Ghost\Support\ContextUtils;
use Commune\Blueprint\Ghost\MindDef\ContextDef;
use Commune\Ghost\Context\Prototype\IContextDef;
use Commune\Blueprint\Ghost\MindDef\AliasesForContext;

/**
 * Context 配置的元数据.
 * 用于定义各种 Context
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property-read string $name      当前配置的 ID
 * @property-read string $title     标题
 * @property-read string $desc      简介
 * @property-read string $wrapper   目标 Wrapper 的类名. 允许用别名.
 * @see Aliases
 *
 * @property-read array $config     wrapper 对应的配置.
 */
class ContextMeta extends AbsMeta
{

    const IDENTITY = 'name';

    public static function stub(): array
    {
        return [
            'name' => '',
            'title' => '',
            'desc' => '',
            'wrapper' => '',
            'config' => [],
        ];
    }

    public function __get_wrapper() : string
    {
        $wrapper = $this->_data['wrapper'] ?? '';
        $wrapper = empty($wrapper)
            ? AliasesForContext::getAliasOfOrigin(IContextDef::class)
            : $wrapper;

        return AliasesForContext::getOriginFromAlias($wrapper);
    }

    public function __set_wrapper(string $name, $value) : string
    {
        $this->_data[$name] = AliasesForContext::getAliasOfOrigin(strval($value));
    }

    public static function relations(): array
    {
        return [];
    }

    public static function validate(array $data): ? string /* errorMsg */
    {
        $contextName = $data['name'] ?? '';
        if (!ContextUtils::isValidContextName($contextName)) {
            return 'context name is invalid';
        }

        return parent::validate($data);
    }

    public static function validateWrapper(string $wrapper): ? string
    {
        $defType = ContextDef::class;
        return is_a($wrapper, $defType, TRUE)
            ? null
            : static::class . " wrapper should be subclass of $defType, $wrapper given";
    }


}