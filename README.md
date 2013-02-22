OOSSH - Object Oriented SSH for PHP
===================================

[![Build Status](https://travis-ci.org/frequence-web/OOSSH.png?branch=master)](https://travis-ci.org/frequence-web/OOSSH)

OOSSH is an easy-to-use object encapsulation of the php SSH2 library.

Basic Usage
-----------

```php

    $oossh = OOSSH\OOSSH::createAndLoad(
        array(
            'foo' => array(
                'host'     => 'foo.bar.baz',
                'username' => 'foo',
                'password' => 'baz',
            )
        )
    );

    $oossh->get('foo')->exec('uname -a', function($stdio, $stderr) {
        echo $stdio;
        if ($stderr) {
            throw new RuntimeException($stderr);
        }
    });

```

TODO
----

 * File handling (SCP)

Contribute
----------

Send me an email yohan@giarelli.org ;)
