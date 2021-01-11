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
use Raylin666\Server\Bootstrap\Event\OnWorkerError;
use Swoole\Server;

/**
 * Class WorkerErrorCallback
 * @package Raylin666\Server\Bootstrap\Callback
 */
class WorkerErrorCallback
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * WorkerErrorCallback constructor.
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
    }

    /**
     * @param Server $server
     * @param int    $worker_id
     * @param int    $worker_pid
     * @param int    $exit_code
     * @param int    $signal
     */
    public function onWorkerError(
        Server $server,
        int $worker_id,
        int $worker_pid,
        int $exit_code,
        int $signal
    ) {
        $this->eventDispatcher->dispatch(
            new OnWorkerError(
                $server,
                $worker_id,
                $worker_pid,
                $exit_code,
                $signal
            )
        );
    }
}