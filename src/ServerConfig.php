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

use Raylin666\Contract\ArrayableInterface;
use Raylin666\Server\Exception\InvalidArgumentException;

/**
 * Class ServerConfig
 * @package Raylin666\Server
 * @method ServerConfig setType(string $type)
 * @method ServerConfig setMode(int $mode)
 * @method ServerConfig setServers(array $servers)
 * @method ServerConfig setProcesses(array $processes)
 * @method ServerConfig setSettings(array $settings)
 * @method ServerConfig setCallbacks(array $callbacks)
 * @method string getType()
 * @method int getMode()
 * @method array getServers()
 * @method array getProcesses()
 * @method array getSettings()
 * @method array getCallbacks()
 */
class ServerConfig implements ArrayableInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * ServerConfig constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        if (empty($config['servers'] ?? [])) {
            throw new InvalidArgumentException('Config servers not exist.');
        }

        $servers = [];
        foreach ($config['servers'] as $item) {
            $servers[] = Port::build($item);
        }

        $this->setType($config['type'] ?? Server::class)
            ->setMode($config['mode'] ?? SWOOLE_BASE)
            ->setServers($servers)
            ->setProcesses($config['processes'] ?? [])
            ->setSettings($config['settings'] ?? [])
            ->setCallbacks($config['callbacks'] ?? []);
    }

    /**
     * @param $name
     * @param $value
     * @return ServerConfig
     */
    public function __set($name, $value): self
    {
        // TODO: Implement __set() method.

        if (! $this->isAvailableProperty($name)) {
            throw new InvalidArgumentException(sprintf('Invalid property %s', $name));
        }
        $this->config[$name] = $value;
        return $this;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        // TODO: Implement __get() method.

        if (! $this->isAvailableProperty($name)) {
            throw new InvalidArgumentException(sprintf('Invalid property %s', $name));
        }

        return $this->config[$name] ?? null;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed|ServerConfig|null
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.

        $prefix = strtolower(substr($name, 0, 3));
        if (in_array($prefix, ['set', 'get'])) {
            $propertyName = strtolower(substr($name, 3));
            if (! $this->isAvailableProperty($propertyName)) {
                throw new InvalidArgumentException(sprintf('Invalid property %s', $propertyName));
            }

            return $prefix === 'set'
                ? $this->__set($propertyName, ...$arguments)
                : $this->__get($propertyName);
        }
    }

    /**
     * @param Port $port
     * @return ServerConfig
     */
    public function addServer(Port $port): ServerConfig
    {
        $this->config['servers'][] = $port;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        // TODO: Implement toArray() method.

        return $this->config;
    }

    /**
     * @param string $name
     * @return bool
     */
    private function isAvailableProperty(string $name): bool
    {
        return in_array($name, [
            'type', 'mode', 'servers', 'processes', 'settings', 'callbacks',
        ]);
    }
}