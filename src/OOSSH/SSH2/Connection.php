<?php

namespace OOSSH\SSH2;

use \OOSSH\Exception as Exception;
use \OOSSH\Authentication\AuthenticationInterface;

class Connection
{
    /**
     * @var resource
     */
    protected $resource;

    /**
     * @var string
     */
    protected $hostname;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var bool false
     */
    protected $isAuthenticated;

    protected $isInBlock;

    protected $commands;

    /**
     * @param string $hostname
     * @param int $port
     */
    public function __construct($hostname, $port = 22)
    {
        $this->hostname = $hostname;
        $this->port = $port;
        $this->isAuthenticated = false;
        $this->isInBlock = false;
        $this->commands = array();
    }

    /**
     * @throws \OOSH\Exception\ConnectionRefused
     * 
     * @return Connection
     */
    public function connect()
    {
        $this->resource = ssh2_connect($this->hostname, $this->port);

        if (false === $this->resource) {
            throw new Exception\ConnectionRefused();
        }

        return $this;
    }

    public function authenticate(AuthenticationInterface $authentication)
    {
        $authentication->authenticate($this->resource);
        $this->isAuthenticated = true;

        return $this;
    }

    public function exec($command, $callback = null)
    {
        if ($this->isInBlock) {
            return $this->addCommand($command);
        }

        $stream = ssh2_exec($this->resource, $command);

        if (null !== $callback) {
            $this->callCallback($stream, $callback);
        }

        return $this;
    }

    public function begin()
    {
        $this->isInBlock = true;

        return $this;
    }

    public function end($callback = null)
    {
        $stream = ssh2_shell($this->resource);

        foreach ($this->commands as $command) {
            fwrite($stream, $command.PHP_EOL);
        }

        if (null !== $callback) {
            $this->callCallback($stream, $callback);
        }

        $this->isInBlock = false;

        return $this;
    }

    protected function addCommand($command)
    {
        $this->commands[] = $command;

        return $this;
    }

    protected function callCallback($stream, $callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('$callback must be a callable');
        }

        $stdio = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
        $stderr = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
        stream_set_blocking($stdio, 1);
        stream_set_blocking($stderr, 1);
        call_user_func($callback, stream_get_contents($stdio), stream_get_contents($stderr));

        return $this;
    }
}