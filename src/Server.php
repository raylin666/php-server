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
use Psr\Container\ContainerInterface;
use Swoole\Http\Server as SwooleHttpServer;
use Raylin666\Server\Contract\ServerInterface;
use Raylin666\Server\Exception\RuntimeException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Raylin666\Server\Contract\EventCallbackAbstract;
use Swoole\WebSocket\Server as SwooleWebSocketServer;
use Raylin666\Server\Bootstrap\Event\BeforeServerStart;

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
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * Server constructor.
     * @param ContainerInterface       $container
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(ContainerInterface $container, EventDispatcherInterface $eventDispatcher)
    {
        $this->container = $container;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
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
     * @return mixed|void
     */
    public function start()
    {
        // TODO: Implement start() method.
        
        $this->getServer()->start();
    }

    /**
     * @return mixed|SwooleServer
     */
    public function getServer()
    {
        // TODO: Implement getServer() method.

        return $this->server;
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
        foreach ($events as $event => $callback) {
            // 必须Swoole的回调事件, 必须继承自 EventCallbackAbstract
            if (
                (! in_array($event, SwooleEvent::getSwooleEvents()))
                || (! $callback instanceof EventCallbackAbstract)
            ) {
                continue ;
            }

            SwooleEvent::on($server, $event, function (...$args) use ($callback) {
                foreach ($callback->register() as $item) {
                    call_user_func($item, ...$args);
                }
            });

            $this->serverCallbacks[sprintf('%s::%s', $event, get_class($callback))] = $serverName;
        }
    }
}