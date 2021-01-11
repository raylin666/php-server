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
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Class OnMessage
 * @package Raylin666\Server\Bootstrap\Event
 */
class OnMessage extends Event
{
    /**
     * @var Server
     */
    public $server;

    /**
     * @var Frame
     */
    public $frame;

    /**
     * OnMessage constructor.
     * @param Server $server
     * @param Frame  $frame
     */
    public function __construct(Server $server, Frame $frame)
    {
        $this->server = $server;
        $this->frame = $frame;
    }

    /**
     * @return string
     */
    public function getEventAccessor(): string
    {
        return SwooleEvent::ON_MESSAGE;
    }
}