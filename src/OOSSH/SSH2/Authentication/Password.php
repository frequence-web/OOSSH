<?php

namespace OOSSH\SSH2\Authentication;

use OOSSH\Authentication\AuthenticationInterface;
use OOSSH\Exception\AuthenticationFailed;

/**
 * Represents a username/password authentication
 *
 * @author Yohan GIARELLI <yohan@giarel.li>
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
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param $resource
     *
     * @throws AuthenticationFailed
     */
    public function authenticate($resource)
    {
        if (!\ssh2_auth_password($resource, $this->username, $this->password)) {
            throw new AuthenticationFailed;
        }
    }
}
