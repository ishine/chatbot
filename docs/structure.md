# 对话机器人的架构设想


目前基本上已经确定了以下几个概念:

1. Ghost : 多轮对话管理内核.
2. Shell : 交互平台. 负责消息的获取, 渲染与发送.
3. Framework : 应用运行框架.

然后存在一些关键性的问题.

## 微服务架构与分布式架构

关键问题在于两种架构方式:

- 微服务/RPC 架构
- 多实例同步架构.

### 微服务架构

这个思路是将多种 shell 作为服务端部署, Ghost 作为一个独立的微服务.

所有 Shell 推送来的消息都由 Ghost 负责解决, 产生同步和异步的响应.
Ghost 作为微服务端, 接受消息需要定好是全异步的, 还是同步的.

另外 Ghost 应该对外提供 API 和 callback, 方便进行管理.
这样就不用拿 Shell 作为一个独立的 API 或者 Callback.

在这个架构下, Shell 就是完全面向用户的, 逻辑上也要简洁一些.


如果是同步通讯, Shell 和 Ghost 可以考虑用 Http.
如果是异步通讯, 则需要考虑 redis/kafka 等管道机制.
容错上看, 显然后者更好一些. 但是要同时使用两种就比较麻烦了.


问题在于:

+ 同步通讯
    - 方便发送同步响应.
    - 缺点是性能难以保障.
+ 异步通讯
    - 异步通讯比较好实现.
    - 异步通讯比较好维护.
    - 无法发送同步响应.
    - 无法保证消息时序.
+ API, Callback, Message 三者的一致性
    - 如果不是 Http 协议, 则 API/Callback/Message 三者要同时对外提供时很困难.
        - 涉及到复杂的多端部署问题.
+ 端相关逻辑
    - 资源可能非多端通用, 比如媒体文件的 url. 如果要通用, 就需要有公共的 Storage
    - Ghost 如果对端无感, 也很麻烦. 比如涉及微信资源.



### 同步架构

每一个服务端实例, 同时具备 shell 和 ghost. 在 ghost 上通过逻辑实现一致性管理.

现在还不能立刻想清楚, 究竟是同步架构容易裂脑, 还是异步架构容易裂脑.


## 同步消息 / 双工消息 / 离线消息 / 拉取消息

现在面临一个问题, 搭建一个对话机器人, 是否本质上要搭建一个独立的即时聊天系统.
这个问题想不清楚, 很多事情没法开展.

作为一个独立的聊天系统, 与客户端的通讯就会变得非常复杂. 至少面临以下几种情况:

- 同步消息 : 随请求同步返回
- 双工消息 : 主动推送给用户, 同时保证连接通畅
- 离线消息 : 在特定的情况下, 推送离线消息给用户. 特定情况还不能限制太死了.
- 拉取消息 : 考虑到端的推送失败等问题, 还可能要客户端主动拉取的机制. 推拉结合.

多种推送方式, 还要保证客户端处理消息的一致性. 而客户端又是平台相关的, 并非可以自主管理.

### 理想的 Chatbot 的消息管理

理想的 Chatbot, 对于每一个 Chat 而言, 无论有多少个 Shell, Ghost 面对的输入是有序的.
同时, Ghost 对外输出的消息也是有序的.

如果客户端是 Wechat, DuerOS 之类, 很明显消息的推拉结合是没有必要的. 客户端自己有一整套的机制.

Shell 维护好自己的