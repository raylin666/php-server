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
 * Class OnPacket
 * @package Raylin666\Server\Bootstrap\Event
 */
class OnPacket extends Event
{
    /**
     * @var Server
     */
    public $server;

    /**
     * @var string
     */
    public $data;

    /**
     * @var array
     */
    public $clientInfo;

    /**
     * OnPacket constructor.
     * @param Server $server
     * @param string $data
     * @param array  $clientInfo
     */
    public function __construct(Server $server, string $data, array $clientInfo)
    {
        $this->server = $server;
        $this->data = $data;
        $this->clientInfo = $clientInfo;
    }

    /**
     * @return string
     */
    public function getEventAccessor(): string
    {
        return SwooleEvent::ON_PACKET;
    }
}