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
 * Class OnFinish
 * @package Raylin666\Server\Bootstrap\Event
 */
class OnFinish extends Event
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
     * @var mixed
     */
    public $data;

    /**
     * OnFinish constructor.
     * @param Server $server
     * @param int    $task_id
     * @param mixed  $data
     */
    public function __construct(Server $server, int $task_id, mixed $data)
    {
        $this->server = $server;
        $this->task_id = $task_id;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getEventAccessor(): string
    {
        return SwooleEvent::ON_FINISH;
    }
}