<?php

namespace OOSSH\SSH2\Authentication;

use \OOSSH\Authentication\AuthenticationInterface;
use \OOSSH\Exception as Exception;

class PublicKeyAuthentication implements AuthenticationInterface
{
    /**
     * @var string
     */
    private $pubkeyFile;

    /**
     * @var string
     */
    private $privkeyFile;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $passphrase;

    /**
     * @param $username
     * @param $pubkeyFile
     * @param $privkeyFile
     * @param null $passphrase
     */
    function __construct($username, $pubkeyFile, $privkeyFile, $passphrase = null)
    {
        $this->username = $username;
        $this->pubkeyFile = $pubkeyFile;
        $this->privkeyFile = $privkeyFile;
        $this->passphrase = $passphrase;
    }

    public function authenticate($resource)
    {
        if (!ssh2_auth_pubkey_file($resource, $this->username, $this->pubkeyFile, $this->privkeyFile, $this->passphrase)) {
            throw new Exception\AuthenticationFailed;
        }
    }
}