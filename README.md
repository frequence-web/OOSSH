OOSSH - Object Oriented SSH for PHP
===================================

OOSSH is an encapsulation of the php SSH2 library.

Warning
-------

OOSSH is not stable

Basic Usage
-----------

    $con = new OOSSH\SSH2\Connection('host', 22);
    $con->connect()
        ->authenticate(new PasswordAuthentication('foo', 'bar'))
        ->exec('cd /home/foo')
        ->exec('ls -al', function($stdio, $stderr) { echo $stdio; })
        ->begin()
          ->exec('cd /var/www')
          ->exec('mv foo bar')
          ->exec('rm -rf cache/*')
          ->exec('exit')
        ->end();

TODO
----

 * File handling (SCP)
 * Refactoring
 * Tests

Contribute
----------

Send me an email yohan@giarelli.org ;)
