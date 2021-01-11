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
use Swoole\Server as SwooleServer;
use Swoole\Http\Server as SwooleHttpServer;

/**
 * Class BeforeMainServerStart
 * @package Raylin666\Server\Bootstrap\Event
 */
class BeforeMainServerStart extends Event
{
    /**
     * @var object|SwooleHttpServer|SwooleServer
     */
    public $server;

    /**
     * @var array
     */
    public $serverConfig;

    /**
     * BeforeMainServerStart constructor.
     * @param       $server
     * @param array $serverConfig
     */
    public function __construct($server, array $serverConfig)
    {
        $this->server = $server;
        $this->serverConfig = $serverConfig;
    }

    /**
     * @return string
     */
    public function getEventAccessor(): string
    {
        return SwooleEvent::ON_BEFORE_MAIN_SERVER_START;
    }
}