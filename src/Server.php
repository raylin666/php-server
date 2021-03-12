<?php
// +----------------------------------------------------------------------
// | Created by linshan. 版权所有 @
// +----------------------------------------------------------------------
// | Copyright (c) 2020 All rights reserved.
// +----------------------------------------------------------------------
// | Technology changes the world . Accumulation makes people grow .
// +----------------------------------------------------------------------
// | Author: kaka梦很美 <1099013371@qq.com>
// +----------------------------------------------------------------------

namespace Raylin666\Server;

use Swoole\Server as SwooleServer;
use Swoole\Http\Server as SwooleHttpServer;
use Raylin666\Server\Contract\ServerInterface;
use Raylin666\Server\Exception\RuntimeException;
use Swoole\WebSocket\Server as SwooleWebSocketServer;
use Raylin666\Server\Contract\EventCallbackInterface;

/**
 * Class Server
 * @package Raylin666\Server
 */
class Server implements ServerInterface
{
    /**
     * @var bool
     */
    protected $enableHttpServer = false;

    /**
     * @var bool
     */
    protected $enableWebSocketServer = false;

    /**
     * @var array
     */
    protected $serverCallbacks = [];

    /**
     * @var SwooleServer
     */
    protected $server;

    /**
     * @var array
     */
    protected $events = [];

    /**
     * Server constructor.
     */
    public function __construct()
    {
        $this->events = SwooleEvent::getSwooleEvents();
    }

    /**
     * 事件添加
     * @param string   $event
     * @param callable $callback
     */
    public function on(string $event, callable $callback)
    {
        if (! $this->events) {
            throw new RuntimeException('请先执行 `init` 函数初始化回调类后再设置回调事件');
        }

        if (array_key_exists($event, $this->events) && ($this->events[$event] instanceof EventCallbackInterface)) {
            $this->events[$event]->addCallback($callback);
        }
    }

    /**
     * 初始化服务
     * @param ServerConfig $config
     * @return ServerInterface
     */
    public function init(ServerConfig $config): ServerInterface
    {
        // TODO: Implement init() method.

        $this->initServers($config);
        return $this;
    }

    /**
     * 获取服务
     * @return mixed|SwooleServer
     */
    public function getServer()
    {
        // TODO: Implement getServer() method.

        return $this->server;
    }

    /**
     * 启动服务
     * @return mixed
     */
    public function start()
    {
        // TODO: Implement start() method.

        return $this->getServer()->start();
    }

    /**
     * @param ServerConfig $config
     */
    protected function initServers(ServerConfig $config)
    {
        $servers = $this->sortServers($config->getServers());

        foreach ($servers as $server) {
            $name = $server->getName();
            $type = $server->getType();
            $host = $server->getHost();
            $port = $server->getPort();
            $sockType = $server->getSockType();
            $callbacks = $server->getCallbacks();

            // 服务不存在则构建服务,服务存在则添加监听
            if (! $this->server instanceof SwooleServer) {
                $this->server = $this->makeServer($type, $host, $port, $config->getMode(), $sockType);
                $callbacks = array_replace($config->getCallbacks(), $callbacks);
                $this->registerSwooleEvents($this->server, $callbacks, $name);
                $this->server->set(array_replace($config->getSettings(), $server->getSettings()));
                ServerManager::add($name, [$type, current($this->server->ports)]);
            } else {
                /** @var bool|\Swoole\Server\Port $slaveServer */
                $slaveServer = $this->server->addlistener($host, $port, $sockType);
                if (! $slaveServer) {
                    throw new RuntimeException("Failed to listen server port [{$host}:{$port}]");
                }
                $server->getSettings() && $slaveServer->set(array_replace($config->getSettings(), $server->getSettings()));
                $this->registerSwooleEvents($slaveServer, $callbacks, $name);
                ServerManager::add($name, [$type, $slaveServer]);
            }
        }
    }

    /**
     * @param Port[] $servers
     * @return Port[]
     */
    protected function sortServers(array $servers)
    {
        $sortServers = [];
        foreach ($servers as $server) {
            switch ($server->getType() ?? 0) {
                case ServerInterface::SERVER_HTTP:
                    $this->enableHttpServer = true;
                    if (! $this->enableWebSocketServer) {
                        array_unshift($sortServers, $server);
                    } else {
                        $sortServers[] = $server;
                    }
                    break;
                case ServerInterface::SERVER_WEBSOCKET:
                    $this->enableWebSocketServer = true;
                    array_unshift($sortServers, $server);
                    break;
                default:
                    $sortServers[] = $server;
                    break;
            }
        }

        return $sortServers;
    }

    /**
     * @param int    $type
     * @param string $host
     * @param int    $port
     * @param int    $mode
     * @param int    $sockType
     * @return SwooleHttpServer|SwooleServer|SwooleWebSocketServer
     */
    protected function makeServer(int $type, string $host, int $port, int $mode, int $sockType)
    {
        switch ($type) {
            case ServerInterface::SERVER_HTTP:
                return $this->createSwooleHttpServer($host, $port, $mode, $sockType);
            case ServerInterface::SERVER_WEBSOCKET:
                return $this->createSwooleWebSocketServer($host, $port, $mode, $sockType);
            case ServerInterface::SERVER_BASE:
                return $this->createSwooleServer($host, $port, $mode, $sockType);
        }

        throw new RuntimeException('Server type is invalid.');
    }

    /**
     * create http server
     * @param $host
     * @param $port
     * @param $mode
     * @param $sockType
     * @return SwooleHttpServer
     */
    protected function createSwooleHttpServer($host, $port, $mode, $sockType): SwooleHttpServer
    {
        return new SwooleHttpServer($host, $port, $mode, $sockType);
    }

    /**
     * create websocket server
     * @param $host
     * @param $port
     * @param $mode
     * @param $sockType
     * @return SwooleWebSocketServer
     */
    protected function createSwooleWebSocketServer($host, $port, $mode, $sockType): SwooleWebSocketServer
    {
        return new SwooleWebSocketServer($host, $port, $mode, $sockType);
    }

    /**
     * create base server
     * @param $host
     * @param $port
     * @param $mode
     * @param $sockType
     * @return SwooleServer
     */
    protected function createSwooleServer($host, $port, $mode, $sockType): SwooleServer
    {
        return new SwooleServer($host, $port, $mode, $sockType);
    }

    /**
     * @param        $server
     * @param array  $events
     * @param string $serverName
     */
    protected function registerSwooleEvents($server, array $events, string $serverName): void
    {
        // 回调事件类替换为实际使用类
        foreach ($events as $keyEvent => $valueCallback) {
            if (array_key_exists($keyEvent, $this->events)) {
                $this->events[$keyEvent] = $valueCallback;
            }
        }

        $this->events = $this->getEventCallbackTransformationObjects($this->events);

        /**
         * @var  $event string
         * @var  $callback  EventCallbackInterface
         */
        foreach ($this->events as $event => $callback) {
            if ($server instanceof SwooleServer\Port) {
                if (in_array($event, [
                    SwooleEvent::ON_WORKER_START,
                    SwooleEvent::ON_WORKER_STOP,
                    SwooleEvent::ON_WORKER_EXIT,
                    SwooleEvent::ON_WORKER_ERROR,
                    SwooleEvent::ON_TASK,
                    SwooleEvent::ON_START,
                    SwooleEvent::ON_SHUTDOWN,
                    SwooleEvent::ON_PIPE_MESSAGE,
                    SwooleEvent::ON_MANAGER_START,
                    SwooleEvent::ON_FINISH,
                    SwooleEvent::ON_MANAGER_STOP,
                ])) {
                    continue ;
                }
            }

            // Swoole 回调事件
            SwooleEvent::on($server, $event, function (...$args) use ($event, $callback) {
                $callback(...$args);
                foreach ($callback->getCallbacks() as $e => $c) {
                    if (is_numeric($e)) {
                        // 类外 $this->on 方法添加的回调事件会将 $callback (当前)类注入到第一个参数
                        call_user_func($c, $callback, ...$args);
                        continue ;
                    }

                    // 类内方法不需要注入 $callback (当前)类
                    call_user_func($c, ...$args);
                }
            });

            $this->serverCallbacks[sprintf('%s::%s', $event, get_class($callback))] = $serverName;
        }
    }

    /**
     * @param array $callbacks
     * @return array
     */
    protected function getEventCallbackTransformationObjects(array $callbacks): array
    {
        $eventCallback = [];

        foreach ($callbacks as $event => $callback) {
            if ($callback = $this->toEventCallbackTransformationObject($event, $callback)) {
                $eventCallback[$event] = $callback;
            }
        }

        return $eventCallback;
    }

    /**
     * @param $event
     * @param $callback
     * @return EventCallbackInterface|null
     */
    protected function toEventCallbackTransformationObject($event, $callback): ?EventCallbackInterface
    {
        try {
            if (array_key_exists($event, $this->events)) {
                if (is_string($callback)) {
                    $callback = new $callback;
                }

                if ($callback instanceof EventCallbackInterface) {
                    return $callback;
                }
            }
        } catch (RuntimeException $e) {}

        return null;
    }
}