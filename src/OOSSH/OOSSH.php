<?php

namespace OOSSH;

use OOSSH\Authentication\AuthenticationInterface;

/**
 * @author Yohan GIARELLI <yohan@giarel.li>
 */
class OOSSH
{
    const VERSION = '0.1.0-dev';

    /**
     * @var ConnectionInterface[]
     */
    protected $connections;

    /**
     * @var AuthenticationInterface[]
     */
    protected $authentications;

    /**
     * @param ConnectionInterface[]     $connections
     * @param AuthenticationInterface[] $authentications
     */
    public function __construct(array $connections = array(), array $authentications = array())
    {
        $this->connections     = $connections;
        $this->authentications = $authentications;
    }

    /**
     * @param string              $name
     * @param ConnectionInterface $connection
     *
     * @return OOSSH
     */
    public function add($name, ConnectionInterface $connection)
    {
        $this->connections[$name] = $connection;

        return $this;
    }

    /**
     * @param string $name
     * @param bool   $autoConnect
     * @param bool   $autoAuthenticate
     *
     * @return ConnectionInterface
     */
    public function get($name, $autoConnect = true, $autoAuthenticate = true)
    {
        $connection = $this->connections[$name];

        if ($autoConnect && !$connection->isConnected()) {
            $connection->connect();
        }

        if ($autoAuthenticate && !$connection->isAuthenticated()) {
            $connection->authenticate($this->authentications[$name]);
        }

        return $connection;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->connections[$name]);
    }

    /**
     * @param string                  $name
     * @param AuthenticationInterface $authentication
     *
     * @return OOSSH
     */
    public function setAuthentication($name, AuthenticationInterface $authentication)
    {
        $this->authentications[$name] = $authentication;

        return $this;
    }

    /**
     * @param ConnectionInterface[]     $connections
     * @param AuthenticationInterface[] $authentications
     *
     * @return OOSSH
     */
    public static function create(array $connections = array(), array $authentications = array())
    {
        return new self($connections, $authentications);
    }
}
