<?php

namespace OOSSH\Authentication;

use \OOSSH\Authentication\AuthenticationInterface;
use \OOSSH\Exception as Exception;

class PublicKeyAuthentication implements AuthenticationInterface
{
    private $pubkeyFile;

    private $privkeyFile;

    private $username;

    function __construct($username, $pubkeyFile, $privkeyFile)
    {
        $this->username = $username;
        $this->pubkeyFile = $pubkeyFile;
        $this->privkeyFile = $privkeyFile;
    }

    public function authenticate($resource)
    {
        if (!ssh2_auth_pubkey_file($resource, $this->username, $this->pubkeyFile, $this->privkeyFile)) {
            throw new Exception\AuthenticationFailed;
        }
    }
}