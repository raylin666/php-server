<?php
// +----------------------------------------------------------------------
// | Created by linshan. 版权所有 @
// +----------------------------------------------------------------------
// | Copyright (c) 2019 All rights reserved.
// +----------------------------------------------------------------------
// | Technology changes the world . Accumulation makes people grow .
// +----------------------------------------------------------------------
// | Author: kaka梦很美 <1099013371@qq.com>
// +----------------------------------------------------------------------

namespace Raylin666\Server\Contract;

/**
 * Interface ServerManangerInterface
 * @package Raylin666\Server\Contract
 */
interface ServerManangerInterface
{
    /**
     * @param string $id
     * @param        $value
     * @return mixed
     */
    public static function add(string $id, $value);

    /**
     * @param string $id
     * @param null   $default
     * @return mixed
     */
    public static function get(string $id, $default = null);

    /**
     * @param string $id
     * @return bool
     */
    public static function has(string $id): bool;

    /**
     * @param string $id
     * @return mixed
     */
    public static function destroy(string $id);

    /**
     * @return array
     */
    public static function all(): array;

    /**
     * flush
     */
    public static function flush(): void;
}