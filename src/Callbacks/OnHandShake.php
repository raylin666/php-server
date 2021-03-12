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

use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * Class OnHandShake
 * @package Raylin666\Server\Callbacks
 */
class OnHandShake extends Callback
{
    /**
     * @var Request
     */
    public $request;

    /**
     * @var Response
     */
    public $response;

    /**
     * @param Request  $request
     * @param Response $response
     */
    public function __invoke(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}