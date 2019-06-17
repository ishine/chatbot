<?php


namespace Commune\Chatbot\Config\Host;

use Commune\Chatbot\App\Commands\AnalyserPipe;
use Commune\Chatbot\App\Commands\UserCommandsPipe;
use Commune\Chatbot\App\SessionPipe\MarkedIntentPipe;
use Commune\Chatbot\App\SessionPipe\NavigationPipe;
use Commune\Support\Option;

/**
 * @property-read int $maxBreakpointHistory
 * @property-read string $rootContextName
 * @property-read int $maxRedirectTimes
 * @property-read int $sessionExpireSeconds
 * @property-read string[] $sessionPipes
 * @property-read string[] $navigatorIntents
 * @property-read MemoryOption[] $memories
 */
class OOHostConfig extends Option
{
    protected static $associations = [
        'memories[]' => MemoryOption::class,
    ];

    public static function stub(): array
    {
        return [
            'rootContextName' => 'rootContextName',
            'maxBreakpointHistory' => 10,
            'maxRedirectTimes' => 20,
            'sessionExpireSeconds' => 3600,
            'sessionPipes' => [
                UserCommandsPipe::class,
                AnalyserPipe::class,
                MarkedIntentPipe::class,
                NavigationPipe::class,
            ],
            'navigatorIntents' => [
                //intentName
            ],
            'memories' => [
                 MemoryOption::stub()
            ]
        ];
    }

}