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

namespace Raylin666\Server;

use Raylin666\Server\Contract\ServerManangerInterface;
use Raylin666\Utils\Traits\Container;

/**
 * Class ServerManager
 * @package Raylin666\Server
 */
class ServerManager implements ServerManangerInterface
{
    use Container;

    /**
     * @var array
     */
    protected static $container = [];
}
