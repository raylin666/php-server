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

use Psr\Container\ContainerInterface;
use Raylin666\Event\EventFactoryInterface;
use Raylin666\Server\Contract\ServerFactoryInterface;
use Raylin666\Server\Contract\ServerInterface;

/**
 * Class ServerFactory
 * @package Raylin666\Server
 */
class ServerFactory implements ServerFactoryInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var EventFactoryInterface
     */
    protected $eventFactory;

    /**
     * ServerFactory constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        // TODO: Implement __invoke() method.

        $this->container = $container;
        $this->eventFactory = $container->get(EventFactoryInterface::class);
    }

    /**
     * @param array $config
     * @return ServerInterface
     */
    public function make(array $config): ServerInterface
    {
        // TODO: Implement make() method.

        $server = $this->getServer();

        $config = $this->getConfig($config);

        $server->init($config);

        return $server;
    }

    /**
     * @return ServerInterface
     */
    protected function getServer(): ServerInterface
    {
        return make(
            Server::class,
            [
                'container'         =>  $this->container,
                'eventDispatcher'   =>  $this->eventFactory->dispatcher()
            ]
        );
    }

    /**
     * @param array $config
     * @return mixed
     */
    protected function getConfig(array $config)
    {
       return make(
            ServerConfig::class,
            [
                'config'    =>   $config
            ]
        );
    }
}