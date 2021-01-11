# Swoole Server

[![GitHub release](https://img.shields.io/github/release/raylin666/server.svg)](https://github.com/raylin666/server/releases)
[![PHP version](https://img.shields.io/badge/php-%3E%207.3-orange.svg)](https://github.com/php/php-src)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](#LICENSE)

### 环境要求

* PHP >=7.3

### 安装说明

```
composer require "raylin666/server"
```

### 使用方式

异步Swoole风格

```php
<?php
require_once 'vendor/autoload.php';

$container = new \Raylin666\Container\Container();

\Raylin666\Util\ApplicationContext::setContainer($container);

// 注册事件监听器
$container->bind(\Raylin666\Contract\ListenerProviderInterface::class, \Raylin666\Event\ListenerProvider::class);
// 注册事件发布器
$container->bind(\Raylin666\Contract\EventDispatcherInterface::class, \Raylin666\Event\Dispatcher::class);
// 注册事件工厂
$container->singleton(\Raylin666\Event\EventFactoryInterface::class, \Raylin666\Event\EventFactory::class);

$config = [
    'mode' => SWOOLE_PROCESS,
    'servers' => [
        [
            'name' => 'websocket',
            'type' => \Raylin666\Server\Contract\ServerInterface::SERVER_WEBSOCKET,
            'host' => '0.0.0.0',
            'port' => 9501,
            'sock_type' => SWOOLE_SOCK_TCP,
            'callbacks' => [
                \Raylin666\Server\SwooleEvent::ON_MESSAGE => [\Raylin666\Server\Bootstrap\Callback\MessageCallback::class, 'onMessage']
            ],
        ],
        [
            'name' => 'http',
            'type' => \Raylin666\Server\Contract\ServerInterface::SERVER_HTTP,
            'host' => '0.0.0.0',
            'port' => 9502,
            'sock_type' => SWOOLE_SOCK_TCP,
            'callbacks' => [
                \Raylin666\Server\SwooleEvent::ON_REQUEST => [\Raylin666\Server\Bootstrap\Callback\RequestCallback::class, 'onRequest'],
            ],
        ],
        [
            'name' => 'base',
            'type' => \Raylin666\Server\Contract\ServerInterface::SERVER_BASE,
            'host' => '0.0.0.0',
            'port' => 9503,
            'sock_type' => SWOOLE_SOCK_TCP,
            'callbacks' => [
                \Raylin666\Server\SwooleEvent::ON_RECEIVE   =>  [\Raylin666\Server\Bootstrap\Callback\ReceiveCallback::class, 'onReceive']
            ],
        ],
    ],
    'settings' => [
        'enable_coroutine' => true,
        'worker_num' => 1, //swoole_cpu_num(),
        'pid_file' => 'runtime/server.pid',
        'open_tcp_nodelay' => true,
        'max_coroutine' => 100000,
        'open_http2_protocol' => true,
        'max_request' => 100000,
        'socket_buffer_size' => 2 * 1024 * 1024,
        'buffer_output_size' => 2 * 1024 * 1024,
    ],
    'callbacks' => [],
];

$container->bind(\Raylin666\Server\Contract\ServerFactoryInterface::class, \Raylin666\Server\ServerFactory::class);

$container->singleton(\Raylin666\Server\Contract\ServerInterface::class, function ($container) use ($config) {
    return $container->get(\Raylin666\Server\Contract\ServerFactoryInterface::class)->make($config);
});

$container->get(\Raylin666\Server\Contract\ServerInterface::class)->start();

```

## 更新日志

请查看 [CHANGELOG.md](CHANGELOG.md)

### 贡献

非常欢迎感兴趣，愿意参与其中，共同打造更好PHP生态，Swoole生态的开发者。

* 在你的系统中使用，将遇到的问题 [反馈](https://github.com/raylin666/server/issues)

### 联系

如果你在使用中遇到问题，请联系: [1099013371@qq.com](mailto:1099013371@qq.com). 博客: [kaka 梦很美](http://www.ls331.com)

## License MIT
