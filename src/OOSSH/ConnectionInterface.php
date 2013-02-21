<?php

namespace OOSSH;

use OOSSH\Authentication\AuthenticationInterface;

/**
 * Base interface that all connection strategy must implement
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
interface ConnectionInterface
{
    /**
     * The connect method.
     * Must not implement authentication
     *
     * @return ConnectionInterface
     */
    public function connect();

    /**
     * This method must check the SSH fingerprint
     *
     * @param string $fingerprint
     * @param mixed  $flags
     *
     * @return ConnectionInterface
     *
     * @throws Exception\BadFingerprint
     */
    public function check($fingerprint, $flags = null);

    /**
     * Method that authenticate after connection by taking
     * an authentication method as argument
     *
     * @param AuthenticationInterface $authentication
     *
     * @return ConnectionInterface
     */
    public function authenticate(AuthenticationInterface $authentication);

    /**
     * Execute a command and gives the result to the callback
     *
     * @param string   $command
     * @param callable $callback
     *
     * @return ConnectionInterface
     */
    public function exec($command, $callback = null);

    /**
     * Begin a shell connection
     *
     * @return ConnectionInterface
     */
    public function begin();

    /**
     * Ends a shell connection and execute stored commands
     *
     * @param callable $callback
     *
     * @return ConnectionInterface
     */
    public function end($callback = null);

    /**
     * @return boolean
     */
    public function isAuthenticated();

    /**
     * @return bool
     */
    public function isConnected();
}
