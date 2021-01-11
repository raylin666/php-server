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
use Raylin666\Server\Bootstrap\Event\OnTask;
use Swoole\Server;

/**
 * Class TaskCallback
 * @package Raylin666\Server\Bootstrap\Callback
 */
class TaskCallback
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * TaskCallback constructor.
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
    }

    /**
     * @param Server $server
     * @param int    $task_id
     * @param int    $src_worker_id
     * @param mixed  $data
     */
    public function onTask(Server $server, int $task_id, int $src_worker_id, mixed $data)
    {
        $this->eventDispatcher->dispatch(
            new OnTask($server, $task_id, $src_worker_id, $data)
        );
    }
}