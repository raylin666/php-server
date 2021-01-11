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

namespace Raylin666\Server\Contract;

use Raylin666\Server\ServerConfig;
use Swoole\Server;

/**
 * Interface ServerInterface
 * @package Raylin666\Server\Contract
 */
interface ServerInterface
{
    /**
     * Http Service
     */
    const SERVER_HTTP = 1;

    /**
     * Websocket Service
     */
    const SERVER_WEBSOCKET = 2;

    /**
     * Base Service
     */
    const SERVER_BASE = 3;

    /**
     * 初始化服务配置
     * @param ServerConfig $config
     * @return ServerInterface
     */
    public function init(ServerConfig $config): ServerInterface;

    /**
     * 服务启动
     * @return mixed
     */
    public function start();

    /**
     * 获取服务
     * @return mixed|Server
     */
    public function getServer();
}