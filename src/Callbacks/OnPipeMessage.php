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
 * Class OnPipeMessage
 * @package Raylin666\Server\Callbacks
 */
class OnPipeMessage extends Callback
{
    /**
     * @var Server
     */
    public $server;

    /**
     * @var int
     */
    public $srcWorkerId;

    /**
     * @var mixed
     */
    public $message;

    /**
     * @param Server $server
     * @param int    $srcWorkerId
     * @param mixed  $message
     */
    public function __invoke(Server $server, int $srcWorkerId, mixed $message)
    {
        $this->server = $server;
        $this->srcWorkerId = $srcWorkerId;
        $this->message = $message;
    }
}