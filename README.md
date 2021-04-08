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

```php
<?php

require_once 'vendor/autoload.php';

use Raylin666\Server\Server;

$server = new Server();

/**
 * 注意(这里有个灵活点): 
 *      下面的 $config 有 callbacks 配置, 默认情况下 Server 已经为你配置了所有的 Swoole 回调事件类。你可能想要修改或添加回调事件类，
 *      而 Server 已经为你提供了两个方案:
 *          方案一: 继承 \Raylin666\Server\Callbacks\... 对应的回调事件类, 在服务启动时, 继承后里面的方法都会被调用, 所以要保证类方法必须是 public, 每个方法的参数都是回调事件中的参数,
 *                 如下 public function request($request, $response) , $request 和 $response 就是对应的 OnRequest 回调事件中的 swoole_http_request 和 swoole_http_response 参数。
 *                 当然, 你还可以使用回调事件类中的变量,比如 OnRequest 中的 $this->request ...等等。最后, 将继承类注册进 callbacks 配置中即可, 如:
 *                 \Raylin666\Server\SwooleEvent::ON_START => OnStart::class
 *          方案二: 通过 Server->on 方法注册回调, 前提是必须在 Server->init 后调用。该方法注册时的第二个回调参数, 第一个参数是回调事件类, 之后的参数为 Swoole 回调事件的参数, 如:
 *                 $server->on(\Raylin666\Server\SwooleEvent::ON_REQUEST, function ($eventCallback, $request, $response) {}), $eventCallback 就是回调事件类, 
 *                 $request 和 $response 就是对应的 OnRequest 回调事件中的 swoole_http_request 和 swoole_http_response 参数。
 */
class OnRequest extends \Raylin666\Server\Callbacks\OnRequest
{
    public function OnRequest(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
    }
}

// 同时设置多个服务 websocket 、 http 、 base , 当然你也可以只设置一个服务。(这里只是一个例子)
$config = [
    'mode' => SWOOLE_PROCESS,
    'servers' => [
        /*[
            'name' => 'websocket',
            'type' => \Raylin666\Server\Contract\ServerInterface::SERVER_WEBSOCKET,
            'host' => '0.0.0.0',
            'port' => 9501,
            'sock_type' => SWOOLE_SOCK_TCP,
            'callbacks' => [
                \Raylin666\Server\SwooleEvent::ON_START => OnStart::class
            ],
        ],*/
        [
            'name' => 'http',
            'type' => \Raylin666\Server\Contract\ServerInterface::SERVER_HTTP,
            'host' => '0.0.0.0',
            'port' => 9502,
            'sock_type' => SWOOLE_SOCK_TCP,
            'callbacks' => [
                \Raylin666\Server\SwooleEvent::ON_REQUEST => OnRequest::class
            ],
        ],
        /*[
            'name' => 'base',
            'type' => \Raylin666\Server\Contract\ServerInterface::SERVER_BASE,
            'host' => '0.0.0.0',
            'port' => 9503,
            'sock_type' => SWOOLE_SOCK_TCP,
            'callbacks' => [],
        ],*/
    ],
    'settings' => [
        'enable_coroutine' => true,
        'worker_num' => swoole_cpu_num(),
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

$server->init(new \Raylin666\Server\ServerConfig($config));

// on 方法调用必须在 $server->init 之后,因为 $server->init 需要初始化回调类
$server->on(\Raylin666\Server\SwooleEvent::ON_REQUEST, function ($eventCallback, $request, $response) {
    var_dump($eventCallback->request);
});

$server->on(\Raylin666\Server\SwooleEvent::ON_START, function () {
    var_dump('onStart');
});

$server->start();

```

## 更新日志

请查看 [CHANGELOG.md](CHANGELOG.md)

### 联系

如果你在使用中遇到问题，请联系: [1099013371@qq.com](mailto:1099013371@qq.com). 博客: [kaka 梦很美](http://www.ls331.com)

## License MIT
