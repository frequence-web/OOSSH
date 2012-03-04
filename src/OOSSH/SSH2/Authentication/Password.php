<?php

namespace OOSSH\SSH2\Authentication;

use OOSSH\Authentication\AuthenticationInterface,
    OOSSH\Exception as Exception;

/**
 * Represents a username/password authentication
 */
class Password implements AuthenticationInterface
{
    /**
     * The authentication username
     *
     * @var string
     */
    protected $username;

    /**
     * The authentication password
     *
     * @var string
     */
    protected $password;

    /**
     * @param $username
     * @param $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param $resource
     * @throws \OOSSH\Exception\AuthenticationFailed
     */
    public function authenticate($resource)
    {
        if (!ssh2_auth_password($resource, $this->username, $this->password)) {
            throw new Exception\AuthenticationFailed;
        }
    }
}
