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

use Raylin666\Contract\FactoryInterface;

/**
 * Interface ServerFactoryInterface
 * @package Raylin666\Server\Contract
 */
interface ServerFactoryInterface extends FactoryInterface
{
    /**
     * @param array $config
     * @return ServerInterface
     */
    public function make(array $config): ServerInterface;
}