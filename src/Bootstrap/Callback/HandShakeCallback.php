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

namespace Raylin666\Server\Bootstrap\Callback;

use Psr\EventDispatcher\EventDispatcherInterface;
use Raylin666\Server\Bootstrap\Event\OnHandShake;
use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * Class HandShakeCallback
 * @package Raylin666\Server\Bootstrap\Callback
 */
class HandShakeCallback
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * HandShakeCallback constructor.
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
    }

    /**
     * @param Request  $request
     * @param Response $response
     */
    public function onHandShake(Request $request, Response $response)
    {
        $this->eventDispatcher->dispatch(
            new OnHandShake($request, $response)
        );
    }
}