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

namespace Raylin666\Server\Bootstrap\Callback;

use Psr\EventDispatcher\EventDispatcherInterface;
use Raylin666\Server\Bootstrap\Event\OnReceive;
use Swoole\Server;

/**
 * Class ReceiveCallback
 * @package Raylin666\Server\Bootstrap\Callback
 */
class ReceiveCallback
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * ReceiveCallback constructor.
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
    }

    /**
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     * @param string $data
     */
    public function onReceive(Server $server, int $fd, int $reactorId, string $data)
    {
        $this->eventDispatcher->dispatch(
            new OnReceive($server, $fd, $reactorId, $data)
        );
    }
}