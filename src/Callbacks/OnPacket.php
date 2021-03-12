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
 * Class OnPacket
 * @package Raylin666\Server\Callbacks
 */
class OnPacket extends Callback
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
     * @param Server $server
     * @param string $data
     * @param array  $clientInfo
     */
    public function __invoke(Server $server, string $data, array $clientInfo)
    {
        $this->server = $server;
        $this->data = $data;
        $this->clientInfo = $clientInfo;
    }
}