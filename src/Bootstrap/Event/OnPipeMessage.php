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
 * Class OnPipeMessage
 * @package Raylin666\Server\Bootstrap\Event
 */
class OnPipeMessage extends Event
{
    /**
     * @var Server
     */
    public $server;

    /**
     * @var int
     */
    public $src_worker_id;

    /**
     * @var mixed|mixed
     */
    public $message;

    /**
     * OnPipeMessage constructor.
     * @param Server $server
     * @param int    $src_worker_id
     * @param mixed  $message
     */
    public function __construct(Server $server, int $src_worker_id, mixed $message)
    {
        $this->server = $server;
        $this->src_worker_id = $src_worker_id;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getEventAccessor(): string
    {
        return SwooleEvent::ON_PIPE_MESSAGE;
    }
}