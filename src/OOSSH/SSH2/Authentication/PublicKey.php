<?php

namespace OOSSH\SSH2\Authentication;

use OOSSH\Authentication\AuthenticationInterface,
    OOSSH\Exception as Exception;

/**
 * Represents a pubkey/privkey authentication
 */
class PublicKey implements AuthenticationInterface
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
        $this->username    = $username;
        $this->pubkeyFile  = $pubkeyFile;
        $this->privkeyFile = $privkeyFile;
        $this->passphrase  = $passphrase;
    }

    /**
     * @param $resource
     * @throws \OOSSH\Exception\AuthenticationFailed
     */
    public function authenticate($resource)
    {
        if (!\ssh2_auth_pubkey_file($resource, $this->username, $this->pubkeyFile, $this->privkeyFile, $this->passphrase)) {
            throw new Exception\AuthenticationFailed;
        }
    }
}
