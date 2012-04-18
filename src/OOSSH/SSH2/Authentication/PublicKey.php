<?php

namespace OOSSH\SSH2\Authentication;

use OOSSH\Authentication\AuthenticationInterface,
    OOSSH\Util\KeyFinder,
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
    function __construct($username, $pubkeyFile = null, $privkeyFile = null, $passphrase = null)
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
        $pubkeyFile = $this->pubkeyFile;
        if (!is_file($pubkeyFile)) {
            $pubkeyFile = KeyFinder::find(KeyFinder::TYPE_PUBLICKEY);
        }

        $privkeyFile = $this->privkeyFile;
        if (!is_file($privkeyFile)) {
            $privkeyFile = KeyFinder::find(KeyFinder::TYPE_PRIVATEKEY);
        }

        if (!\ssh2_auth_pubkey_file($resource, $this->username, $pubkeyFile, $privkeyFile, $this->passphrase)) {
            throw new Exception\AuthenticationFailed;
        }
    }

    /**
     * @param string $passphrase
     */
    public function setPassphrase($passphrase)
    {
        $this->passphrase = $passphrase;
    }

    /**
     * @return string
     */
    public function getPassphrase()
    {
        return $this->passphrase;
    }

    /**
     * @param string $privkeyFile
     */
    public function setPrivkeyFile($privkeyFile)
    {
        $this->privkeyFile = $privkeyFile;
    }

    /**
     * @return string
     */
    public function getPrivkeyFile()
    {
        return $this->privkeyFile;
    }

    /**
     * @param string $pubkeyFile
     */
    public function setPubkeyFile($pubkeyFile)
    {
        $this->pubkeyFile = $pubkeyFile;
    }

    /**
     * @return string
     */
    public function getPubkeyFile()
    {
        return $this->pubkeyFile;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
}
