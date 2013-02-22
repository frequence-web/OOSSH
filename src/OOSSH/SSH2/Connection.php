<?php

namespace OOSSH\SSH2;

use OOSSH\Authentication\AuthenticationInterface;
use OOSSH\ConnectionInterface;
use OOSSH\Exception\BadFingerprint;
use OOSSH\Exception\ConnectionRefused;

/**
 * @author Yohan GIARELLI <yohan@giarel.li>
 */
class Connection implements ConnectionInterface
{
    const
        FINGERPRINT_MD5  = SSH2_FINGERPRINT_MD5,
        FINGERPRINT_SHA1 = SSH2_FINGERPRINT_SHA1,
        FINGERPRINT_HEX  = SSH2_FINGERPRINT_HEX,
        FINGERPRINT_RAW  = SSH2_FINGERPRINT_RAW;

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

    /**
     * @var bool
     */
    protected $isConnected;

    /**
     * @var bool
     */
    protected $isInBlock;

    /**
     * @var string[]
     */
    protected $commands = array();

    /**
     * @param string $hostname
     * @param int    $port
     */
    public function __construct($hostname, $port = 22)
    {
        $this->hostname        = $hostname;
        $this->port            = $port;
        $this->isAuthenticated = false;
        $this->isConnected     = false;
        $this->isInBlock       = false;
    }

    /**
     * {@inheritDoc}
     */
    public function connect()
    {
        $this->resource = ssh2_connect($this->hostname, $this->port);

        if (false === $this->resource) {
            throw new ConnectionRefused;
        }

        $this->isConnected = true;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function check($fingerprint, $flags = null)
    {
        $flags = null === $flags ? self::FINGERPRINT_MD5 | self::FINGERPRINT_HEX : $flags;

        if (strtoupper(ssh2_fingerprint($this->resource, $flags)) !== str_replace(':', '', strtoupper($fingerprint))) {
            throw new BadFingerprint;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate(AuthenticationInterface $authentication)
    {
        $authentication->authenticate($this->resource);
        $this->isAuthenticated = true;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
    public function begin()
    {
        $this->isInBlock = true;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function end($callback = null)
    {
        $stream = fopen(sprintf('ssh2.shell://%s/xterm', $this->resource), 'r+');

        foreach ($this->commands as $command) {
            fwrite($stream, $command."\n");
        }

        if (null !== $callback) {
            $this->callCallback($stream, $callback);
        }

        $this->isInBlock = false;
        $this->commands  = array();

        return $this;
    }

    /**
     * @param string $command
     *
     * @return Connection
     */
    protected function addCommand($command)
    {
        $this->commands[] = $command;

        return $this;
    }

    /**
     * @param $stream
     * @param callable $callback
     * @param int      $waitTime
     *
     * @return Connection
     *
     * @throws \InvalidArgumentException
     */
    protected function callCallback($stream, $callback, $waitTime = 500)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('$callback must be a callable');
        }

        $stdio  = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
        $stderr = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
        stream_set_blocking($stdio, 0);
        stream_set_blocking($stderr, 0);

        do {
            // Hacky way to retrieve ssh stream, which is not select()able
            usleep($waitTime * 1000);
            $io  = stream_get_contents($stdio);
            $err = stream_get_contents($stderr);
            if ($io || $err) {
                call_user_func($callback, $io, $err);
            }
        } while ($io || $err);

        return $this;
    }

    /**
     * @param boolean $isAuthenticated
     */
    public function setIsAuthenticated($isAuthenticated)
    {
        $this->isAuthenticated = $isAuthenticated;
    }

    /**
     * {@inheritDoc}
     */
    public function isAuthenticated()
    {
        return $this->isAuthenticated;
    }

    /**
     * {@inheritDoc}
     */
    public function isConnected()
    {
        return $this->isConnected;
    }
}
