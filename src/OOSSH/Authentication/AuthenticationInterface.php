<?php

namespace OOSSH\Authentication;

/**
 * This is the base interface for the authentication
 */
interface AuthenticationInterface
{
    /**
     * @abstract
     * @param $resource
     */
    public function authenticate($resource);
}
