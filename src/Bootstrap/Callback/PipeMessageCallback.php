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
use Raylin666\Server\Bootstrap\Event\OnPipeMessage;
use Swoole\Server;

/**
 * Class PipeMessageCallback
 * @package Raylin666\Server\Bootstrap\Callback
 */
class PipeMessageCallback
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * PipeMessageCallback constructor.
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
    }

    /**
     * @param Server $server
     * @param int    $src_worker_id
     * @param mixed  $message
     */
    public function onPipeMessage(Server $server, int $src_worker_id, mixed $message)
    {
        $this->eventDispatcher->dispatch(
            new OnPipeMessage($server, $src_worker_id, $message)
        );
    }
}