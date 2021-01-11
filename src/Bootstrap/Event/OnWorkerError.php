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

namespace Raylin666\Server\Bootstrap\Event;

use Raylin666\Event\Event;
use Raylin666\Server\SwooleEvent;
use Swoole\Server;

/**
 * Class OnWorkerError
 * @package Raylin666\Server\Bootstrap\Event
 */
class OnWorkerError extends Event
{
    /**
     * @var Server
     */
    public $server;

    /**
     * @var int
     */
    public $worker_id;

    /**
     * @var int
     */
    public $worker_pid;

    /**
     * @var int
     */
    public $exit_code;

    /**
     * @var int
     */
    public $signal;

    /**
     * OnWorkerError constructor.
     * @param Server $server
     * @param int    $worker_id
     * @param int    $worker_pid
     * @param int    $exit_code
     * @param int    $signal
     */
    public function __construct(
        Server $server,
        int $worker_id,
        int $worker_pid,
        int $exit_code,
        int $signal
    ) {
        $this->server = $server;
        $this->worker_id = $worker_id;
        $this->worker_pid = $worker_pid;
        $this->exit_code = $exit_code;
        $this->signal = $signal;
    }

    /**
     * @return string
     */
    public function getEventAccessor(): string
    {
        return SwooleEvent::ON_WORKER_ERROR;
    }
}