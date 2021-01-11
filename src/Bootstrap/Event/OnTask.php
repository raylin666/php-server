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
 * Class OnTask
 * @package Raylin666\Server\Bootstrap\Event
 */
class OnTask extends Event
{
    /**
     * @var Server
     */
    public $server;

    /**
     * @var int
     */
    public $task_id;

    /**
     * @var int
     */
    public $src_worker_id;

    /**
     * @var mixed|mixed
     */
    public $data;

    /**
     * OnTask constructor.
     * @param Server $server
     * @param int    $task_id
     * @param int    $src_worker_id
     * @param mixed  $data
     */
    public function __construct(Server $server, int $task_id, int $src_worker_id, mixed $data)
    {
        $this->server = $server;
        $this->task_id = $task_id;
        $this->src_worker_id = $src_worker_id;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getEventAccessor(): string
    {
        return SwooleEvent::ON_TASK;
    }
}