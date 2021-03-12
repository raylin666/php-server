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

namespace Raylin666\Server\Callbacks;

use Swoole\WebSocket\Server;

/**
 * Class OnMessage
 * @package Raylin666\Server\Callbacks
 */
class OnMessage extends Callback
{
    /**
     * @var Server
     */
    public $server;

    /**
     * @var
     */
    public $frame;

    /**
     * @param Server $server
     * @param        $frame
     */
    public function __invoke(Server $server, $frame)
    {
        $this->server = $server;
        $this->frame = $frame;
    }
}