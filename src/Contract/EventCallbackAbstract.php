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

/**
 * Class EventCallbackAbstract
 * @package Raylin666\Server\Contract
 */
abstract class EventCallbackAbstract
{
    /**
     * 回调事件注册器
     * @return array
     */
    abstract public function register(): array;
}