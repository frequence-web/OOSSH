<?php

namespace OOSSH\Loader;

use OOSSH\OOSSH;

/**
 * The base interface that all loaders must implements
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
interface LoaderInterface
{
    /**
     * @param OOSSH $oossh
     */
    public function load(OOSSH $oossh);
}
