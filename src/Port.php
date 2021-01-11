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

use Raylin666\Server\Contract\ServerInterface;

/**
 * Class Port
 * @package Raylin666\Server
 */
class Port
{
    /**
     * @var string
     */
    protected $name = 'http';

    /**
     * @var int
     */
    protected $type = ServerInterface::SERVER_HTTP;

    /**
     * @var string
     */
    protected $host = '0.0.0.0';

    /**
     * @var int
     */
    protected $port = 9501;

    /**
     * @var int
     */
    protected $sockType = SWOOLE_SOCK_TCP;

    /**
     * @var array
     */
    protected $callbacks = [];

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @param array $config
     * @return Port
     */
    public static function build(array $config)
    {
        $port = new static();

        $config = self::filter($config);
        isset($config['name']) && $port->setName($config['name']);
        isset($config['type']) && $port->setType($config['type']);
        isset($config['host']) && $port->setHost($config['host']);
        isset($config['port']) && $port->setPort($config['port']);
        isset($config['sock_type']) && $port->setSockType($config['sock_type']);
        isset($config['callbacks']) && $port->setCallbacks($config['callbacks']);
        isset($config['settings'])  && $port->setSettings($config['settings']);

        return $port;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Port
     */
    public function setName(string $name): Port
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return Port
     */
    public function setType(int $type): Port
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return Port
     */
    public function setHost(string $host): Port
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return Port
     */
    public function setPort(int $port): Port
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return int
     */
    public function getSockType(): int
    {
        return $this->sockType;
    }

    /**
     * @param int $sockType
     * @return Port
     */
    public function setSockType(int $sockType): Port
    {
        $this->sockType = $sockType;
        return $this;
    }

    /**
     * @return array
     */
    public function getCallbacks(): array
    {
        return $this->callbacks;
    }

    /**
     * @param array $callbacks
     * @return Port
     */
    public function setCallbacks(array $callbacks): Port
    {
        $this->callbacks = $callbacks;
        return $this;
    }

    /**
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * @param array $settings
     * @return Port
     */
    public function setSettings(array $settings): Port
    {
        $this->settings = $settings;
        return $this;
    }

    /**
     * @param array $config
     * @return array
     */
    private static function filter(array $config): array
    {
        if ((int) $config['type'] === ServerInterface::SERVER_BASE) {
            $default = [
                'open_http2_protocol' => false,
                'open_http_protocol'  => false,
            ];

            $config['settings'] = array_merge($default, $config['settings'] ?? []);
        }

        return $config;
    }
}
