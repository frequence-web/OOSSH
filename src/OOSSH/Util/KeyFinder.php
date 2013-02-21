<?php

namespace OOSSH\Util;

use Symfony\Component\Finder\Finder;

/**
 * Some utils used to find priv/pub keys
 *
 * @author Yohan GIARELLI <yohan@giarel.li>
 */
class KeyFinder
{
    const
        TYPE_PUBLICKEY  = 0,
        TYPE_PRIVATEKEY = 1
    ;

    /**
     * Returns the current user home directory
     *
     * @return string|null
     */
    public static function findUserDir()
    {
        $userInfos = posix_getpwnam(posix_getlogin());

        return isset($userInfos['dir']) ? $userInfos['dir'] : null;
    }

    /**
     * @param int    $type
     * @param string $dir
     *
     * @return string|null
     */
    public static function find($type = self::TYPE_PRIVATEKEY, $dir = null)
    {
        $dir = $dir ?: self::findUserDir().'/.ssh';
        if (0 === strpos($dir, '~')) {
            $dir = self::findUserDir() . substr($dir, 1);
        }

        $iterator = Finder::create()
            ->files()
            ->name('id_?sa'.($type == self::TYPE_PUBLICKEY ? '.pub' : ''))
            ->depth(0)
            ->in($dir)
        ;

        foreach ($iterator as $file) {
            return $file->getPathName();
        }

        return null;
    }

}
