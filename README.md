

开发中.

可以执行 ``` php demo/test.php ``` 做测试.


## 目标feature

-   基础
    -   所有IM平台通用, 对输入输出进行统一抽象化
    -   可分布式部署
    -   不依赖单进程模型, 可以在swoole, workman或 roadrunner 中运行.
    -   demo 目标支持 命令行 + wechat
-   应用
    -   分布式响应时, 不发生逻辑冲突
    -   完全记录所有消息
    -   响应消息通过可拓展的管道机制
    -   支持NLP单元作为中间件
    -   webhook
    -   完整的语境系统
        -   目前方案采用命令式响应
        -   语境的路由层完全可配置 (不依赖代码实现应用逻辑)
        -   有状态的上下文, 允许各种场景转移
        -   面向 scope 的语境记忆
        -   支持 开放域 / 有限域 / 封闭域 响应
    -   兼容botman 式api
    -   支持基本的对话模式
        -   ask
        -   choose
        -   confirm


## 开发计划

[开发计划](docs/plan.md)

## 更新内容

[更新内容](docs/release.md)
