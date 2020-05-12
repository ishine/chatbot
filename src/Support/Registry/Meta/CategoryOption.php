<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Support\Registry\Meta;

use Commune\Support\Option\AbsOption;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property-read string $name              分类的名称. 同一个名称下可以有多个分类.
 *
 * @property-read string $optionClass       storage 里存储的 option的类名
 *
 * @property-read int $cacheExpire          Category 缓存对象过期时间. 是整点自动过期.
 *
 * @property-read StorageMeta $storage
 * 获取数据的 storage.
 *
 * @property-read StorageMeta|null $initialStorage
 * 初始化时使用的 Storage
 *
 *
 */
class CategoryOption extends AbsOption
{
    const IDENTITY = 'name';

    public static function stub(): array
    {
        return [
            'name' => '',
            'cacheExpire' => 600,
            'optionClass' => '',
            'storage' => [],
            'initialStorage' => null,
        ];
    }

    public static function relations(): array
    {
        return [
            'storage' => StorageMeta::class,
            'initialStorage' => StorageMeta::class
        ];
    }

}