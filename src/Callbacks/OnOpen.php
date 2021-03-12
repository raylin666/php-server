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
 * Class OnOpen
 * @package Raylin666\Server\Callbacks
 */
class OnOpen extends Callback
{
    /**
     * @var Server
     */
    public $server;

    /**
     * @var
     */
    public $request;

    /**
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     */
    public function __invoke(Server $server, $request)
    {
        $this->server = $server;
        $this->request = $request;
    }
}