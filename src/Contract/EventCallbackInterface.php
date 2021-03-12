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
 * Interface EventCallbackInterface
 * @package Raylin666\Server\Contract
 */
interface EventCallbackInterface
{
    /**
     * @param callable $callback
     * @param int      $priority
     * @return mixed
     */
    public function addCallback(callable $callback);

    /**
     * @return mixed
     */
    public function getCallbacks();
}