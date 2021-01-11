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
 * Class OnWorkerStart
 * @package Raylin666\Server\Bootstrap\Event
 */
class OnWorkerStart extends Event
{
    /**
     * @var Server
     */
    public $server;

    /**
     * @var int
     */
    public $workerId;

    /**
     * OnWorkerStart constructor.
     * @param Server $server
     * @param int    $workerId
     */
    public function __construct(Server $server, int $workerId)
    {
        $this->server = $server;
        $this->workerId = $workerId;
    }

    /**
     * @return string
     */
    public function getEventAccessor(): string
    {
        return SwooleEvent::ON_WORKER_START;
    }
}