<?php

namespace OOSSH\SSH2\Authentication;

use \OOSSH\Authentication\AuthenticationInterface;
use \OOSSH\Exception as Exception;

class PasswordAuthentication implements AuthenticationInterface
{
    protected $username;

    protected $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function authenticate($resource)
    {
        if (!ssh2_auth_password($resource, $this->username, $this->password)) {
            throw new Exception\AuthenticationFailed;
        }

        return $resource;
    }
}
