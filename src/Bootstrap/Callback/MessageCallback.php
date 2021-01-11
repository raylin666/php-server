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
use Raylin666\Server\Bootstrap\Event\OnMessage;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Class MessageCallback
 * @package Raylin666\Server\Bootstrap\Callback
 */
class MessageCallback
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * MessageCallback constructor.
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
    }

    /**
     * @param Server $server
     * @param Frame  $frame
     */
    public function onMessage(Server $server, Frame $frame)
    {
        $this->eventDispatcher->dispatch(
            new OnMessage($server, $frame)
        );
    }
}