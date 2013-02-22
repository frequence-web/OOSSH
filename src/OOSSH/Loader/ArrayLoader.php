<?php

namespace OOSSH\Loader;

use OOSSH\OOSSH;
use OOSSH\SSH2\Authentication\Password;
use OOSSH\SSH2\Authentication\PublicKey;
use OOSSH\SSH2\Connection;

/**
 *
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class ArrayLoader implements LoaderInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->setConfig($config);
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function load(OOSSH $oossh)
    {
        foreach ($this->config as $name => $config) {
            $this->loadConnection($oossh, $name, $config);
        }
    }

    /**
     * @param OOSSH  $oossh
     * @param string $name
     * @param array  $config
     *
     * @throws \InvalidArgumentException
     */
    protected function loadConnection(OOSSH $oossh, $name, array $config)
    {
        if (!isset($config['host'])) {
            throw new \InvalidArgumentException('Host required');
        }

        $oossh->add($name, new Connection($config['host'], isset($config['port']) ? $config['port'] : 22));

        if (!isset($config['username'])) {
            throw new \InvalidArgumentException('Username required');
        }

        $auth     = null;
        $username = $config['username'];
        if (isset($config['password'])) {
            $auth = new Password($username, $config['password']);
        } else {
            $auth = new PublicKey(
                $username,
                isset($config['pubkey'])     ? $config['pubkey']     : null,
                isset($config['privkey'])    ? $config['privkey']    : null,
                isset($config['passphrase']) ? $config['passphrase'] : null
            );
        }

        $oossh->setAuthentication($name, $auth);
    }
}
