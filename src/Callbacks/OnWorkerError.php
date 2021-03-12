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
 * Class OnWorkerError
 * @package Raylin666\Server\Callbacks
 */
class OnWorkerError extends Callback
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
     * @var int
     */
    public $workerPid;

    /**
     * @var int
     */
    public $exitCode;

    /**
     * @var int
     */
    public $signal;

    /**
     * @param Server $server
     * @param int    $workerId
     * @param int    $workerPid
     * @param int    $exitCode
     * @param int    $signal
     */
    public function __invoke(Server $server, int $workerId, int $workerPid, int $exitCode, int $signal)
    {
        $this->server = $server;
        $this->workerId = $workerId;
        $this->workerPid = $workerPid;
        $this->exitCode = $exitCode;
        $this->signal = $signal;
    }
}