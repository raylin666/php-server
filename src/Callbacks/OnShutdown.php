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
 * Class OnShutdown
 * @package Raylin666\Server\Callbacks
 */
class OnShutdown extends Callback
{
    /**
     * @var Server
     */
    public $server;

    /**
     * @param Server $server
     */
    public function __invoke(Server $server)
    {
        $this->server = $server;
    }
}