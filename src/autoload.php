<?php

/**
 * Warning :
 * You can require this file to autoload OOSSH.
 * But you'd better to use a real autoload.
 * See Symfony2 ClassLoader for example (https://github.com/symfony/ClassLoader)
 */

spl_autoload_register(function($className)
{
    if (is_file($file = sprintf('%s/%s.php', __DIR__, str_replace('\\', '/', $className)))) {
        require_once $file;

        return true;
    }

    return false;
});
