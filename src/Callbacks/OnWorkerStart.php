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
 * Class OnWorkerStart
 * @package Raylin666\Server\Callbacks
 */
class OnWorkerStart extends Callback
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
     * @param Server $server
     * @param int    $workerId
     */
    public function __invoke(Server $server, int $workerId)
    {
        $this->server = $server;
        $this->workerId = $workerId;
    }
}