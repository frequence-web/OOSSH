<?php

namespace OOSSH\SSH2\Authentication;

use OOSSH\Authentication\AuthenticationInterface,
    OOSSH\Exception\AuthenticationFailed;

class HostbasedFile implements AuthenticationInterface
{
    /**
     * The username
     *
     * @var string
     */
    protected $username;

    /**
     * the hostname
     *
     * @var string
     */
    protected $hostname;

    /**
     * The pubkey file path
     *
     * @var string
     */
    protected $pubkeyFile;

    /**
     * The privkey file path
     *
     * @var string
     */
    protected $privkeyFile;

    /**
     * The passphrase
     *
     * @var null|string
     */
    protected $passphrase;

    /**
     * the local user
     *
     * @var null|string
     */
    protected $localUser;

    /**
     * @param $username
     * @param $hostname
     * @param $pubkeyFile
     * @param $privkeyFile
     * @param null $passphrase
     * @param null $localUser
     */
    public function __construct($username, $hostname, $pubkeyFile, $privkeyFile, $passphrase = null, $localUser = null)
    {
        $this->username    = $username;
        $this->hostname    = $hostname;
        $this->pubkeyFile  = $pubkeyFile;
        $this->privkeyFile = $privkeyFile;
        $this->passphrase  = $passphrase;
        $this->localUser   = $localUser;
    }

    /**
     * @param $resource
     * @return mixed
     */
    public function authenticate($resource)
    {
        if (true !== \ssh2_auth_hostbased_file(
            $resource,
            $this->username,
            $this->pubkeyFile,
            $this->privkeyFile,
            $this->passphrase,
            $this->localUser
        )) {
            throw new AuthenticationFailed;
        }
    }
}
