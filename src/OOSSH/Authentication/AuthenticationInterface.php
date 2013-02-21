<?php

namespace OOSSH\Authentication;

/**
 * This is the base interface for authentication strategies
 *
 * @author Yohan GIARELLI <yohan@giarel.li>
 */
interface AuthenticationInterface
{
    /**
     * This method takes the SSH connection resource as argument, and process to authentication
     *
     * @param mixed $resource
     */
    public function authenticate($resource);
}
