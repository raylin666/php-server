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

use Raylin666\Server\Contract\EventCallbackInterface;

/**
 * Class Callback
 * @package Raylin666\Server\Callbacks
 */
abstract class Callback implements EventCallbackInterface
{
    /**
     * @var array
     */
    protected $callbacks = [];

    /**
     * 避免外部继承导致 Server 实例化时出现异常(如在构造函数有必传参数)
     * Callback constructor.
     */
    final public function __construct()
    {
    }

    /**
     * @param callable $callback
     * @return mixed|void
     */
    public function addCallback(callable $callback)
    {
        // TODO: Implement addCallback() method.

        $this->callbacks[] = $callback;
    }

    /**
     * @return array|mixed
     */
    public function getCallbacks()
    {
        // TODO: Implement getCallbacks() method.

        $functions = get_class_methods($this);

        foreach ($functions as $function) {
            if ($this->isAvailableProperty($function)) {
                continue ;
            }

            $this->callbacks[$function] = function (...$args) use ($function) {
                return $this->$function(...$args);
            };
        }

        return $this->callbacks;
    }

    /**
     * @param string $name
     * @return bool
     */
    private function isAvailableProperty(string $name): bool
    {
        return in_array($name, [
            '__invoke', '__construct', 'addCallback', 'getCallbacks', 'isAvailableProperty'
        ]);
    }
}