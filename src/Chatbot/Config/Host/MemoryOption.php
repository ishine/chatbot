<?php


namespace Commune\Chatbot\Config\Host;


use Commune\Chatbot\OOHost\Session\Scope;
use Commune\Support\Option;

/**
 * @property-read string $name
 * @property-read string $desc
 * @property-read string[] $scopes
 * @property-read string[] $entities
 */
class MemoryOption extends Option
{
    public static function stub(): array
    {
        return [
            'name' => 'sandbox',
            'desc' => 'description',
            'scopes' => [Scope::SESSION_ID],
            'entities' => [
                'test'
            ]
        ];
    }

}