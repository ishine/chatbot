<?php
/**
 * Created by PhpStorm.
 * User: BrightRed
 * Date: 2019/4/14
 * Time: 5:05 PM
 */

namespace Commune\Chatbot\Framework\Bootstrap;



use Commune\Chatbot\Blueprint\Conversation\Chat;
use Commune\Chatbot\Blueprint\Conversation\IncomingMessage;
use Commune\Chatbot\Blueprint\Conversation\User;
use Commune\Chatbot\OOHost\Session\Driver as SessionDriver;
use Commune\Container\ContainerContract;
use Commune\Chatbot\Config\ChatbotConfig;
use Commune\Chatbot\Contracts\Translator;
use Commune\Chatbot\Contracts\CacheAdapter;
use Commune\Chatbot\Contracts\ChatServer;
use Commune\Chatbot\Contracts\EventDispatcher;
use Commune\Chatbot\Contracts\ExceptionHandler;
use Commune\Chatbot\Blueprint\Application;
use Commune\Chatbot\Blueprint\Kernel;
use Commune\Chatbot\Blueprint\Conversation\Monologue;

use Commune\Chatbot\Framework\Exceptions\ConfigureException;
use Psr\Log\LoggerInterface;

/**
 * 校验当前必须要的绑定.
 *
 * Class ContractsValidator
 * @package Commune\Chatbot\Framework\Bootstrap
 * @author thirdgerb <thirdgerb@gmail.com>
 */
class ContractsValidator implements Bootstrapper
{

    /**
     * 默认要在 reactor 容器中绑定的组件.
     * @var array
     */
    protected $reactorContracts = [
        // 系统组件的自我绑定
        Application::class,
        ChatServer::class,
        // 系统日志
        LoggerInterface::class,
        // 必须绑定的配置
        ChatbotConfig::class,
        // 内核绑定.
        Kernel::class,
        ExceptionHandler::class,
        // 多请求复用的组件.
        Translator::class,
    ];

    /**
     * 默认在 conversation 容器中绑定的组件.
     * @var array
     */
    protected $conversationContracts = [
        Monologue::class,
        // 系统日志
        LoggerInterface::class,
        // 依赖会话级容器的.
        EventDispatcher::class,
        // 有IO 开销, 考虑IO非阻塞实现的
        CacheAdapter::class,
        // conversation
        User::class,
        Chat::class,
        IncomingMessage::class,
        // host
        SessionDriver::class
    ];

    public function bootstrap(Application $app): void
    {
        $this->validateContracts($app);
    }

    /**
     * 检查系统默认的组件是否已经正确绑定了.
     * @param Application $app
     */
    protected function validateContracts(Application $app)
    {
        $logger = $app->getReactorLogger();

        $logger->debug("check reactor contracts has been bound correctly");
        foreach ($this->reactorContracts as $name) {
            $this->assertBound($logger, $app->getReactorContainer(), $name);
        }

        $logger->debug("check conversation contracts has been bound correctly");
        foreach ($this->conversationContracts as $name) {
            $this->assertBound($logger, $app->getConversationContainer(), $name);
        }

    }

    protected function assertBound(
        LoggerInterface $logger,
        ContainerContract $container,
        string $abstract
    ) : void
    {
        // 如果conversation 的contract 绑定到了 base里, 至少报一个warning
        if (! $container->bound($abstract)) {
            $logger->warning("chatbot contract abstract $abstract not bound at container " . get_class($container));
        }

        // 如果base 里都没有绑定, 则中断进程.
        if (! $container->has($abstract)) {
            throw new ConfigureException("chatbot contract abstract $abstract not bound at container " . get_class($container));
        }
    }




}