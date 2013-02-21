<?php

namespace OOSSH\SSH2\Authentication;

use OOSSH\Authentication\AuthenticationInterface;
use OOSSH\Exception\AuthenticationFailed;

/**
 * @author Yohan GIARELLI <yohan@giarel.li>
 */
class None implements AuthenticationInterface
{
    /**
     * The username
     *
     * @var string
     */
    protected $username;

    /**
     * @param $username
     */
    public function __construct($username)
    {
        $this->username = $username;
    }

    /**
     * @param $resource
     *
     * @throws AuthenticationFailed
     */
    public function authenticate($resource)
    {
        if (true !== \ssh2_auth_none($resource, $this->username)) {
            throw new AuthenticationFailed;
        }
    }
}
