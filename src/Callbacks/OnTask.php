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
 * Class OnTask
 * @package Raylin666\Server\Callbacks
 */
class OnTask extends Callback
{
    /**
     * @var Server
     */
    public $server;

    /**
     * @var int
     */
    public $taskId;

    /**
     * @var int
     */
    public $srcWorkerId;

    /**
     * @var mixed
     */
    public $data;

    /**
     * @param Server $server
     * @param int    $taskId
     * @param int    $srcWorkerId
     * @param mixed  $data
     */
    public function __invoke(Server $server, int $taskId, int $srcWorkerId, mixed $data)
    {
        $this->server = $server;
        $this->taskId = $taskId;
        $this->srcWorkerId = $srcWorkerId;
        $this->data = $data;
    }
}