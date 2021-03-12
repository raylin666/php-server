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

use Swoole\Server;

/**
 * Class OnReceive
 * @package Raylin666\Server\Callbacks
 */
class OnReceive extends Callback
{
    /**
     * @var Server
     */
    public $server;

    /**
     * @var int
     */
    public $fd;

    /**
     * @var int
     */
    public $reactorId;

    /**
     * @var string
     */
    public $data;

    /**
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     * @param string $data
     */
    public function __invoke(Server $server, int $fd, int $reactorId, string $data)
    {
        $this->server = $server;
        $this->fd = $fd;
        $this->reactorId = $reactorId;
        $this->data = $data;
    }
}