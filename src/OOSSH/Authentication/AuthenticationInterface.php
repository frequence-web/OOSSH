<?php

namespace OOSSH\Authentication;

interface AuthenticationInterface
{
    public function authenticate($resource);
}
