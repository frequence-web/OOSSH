<?php

namespace OOSSH\Tests\Loader;

use OOSSH\Loader\ArrayLoader;

/**
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class ArrayLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ArrayLoader
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new ArrayLoader(
            array(
                'con1' => array(
                    'host'     => '::1',
                    'username' => 'foo',
                    'password' => 'bar'
                ),
                'con2' => array(
                    'host'     => 'foo.bar.baz',
                    'username' => 'wat'
                )
            )
        );
    }

    public function testLoad()
    {
        $oossh = $this->getMock('OOSSH\OOSSH');

        $oossh
            ->expects($this->at(0))
            ->method('add')
            ->with('con1', new \PHPUnit_Framework_Constraint_IsInstanceOf('OOSSH\SSH2\Connection'));

        $oossh
            ->expects($this->at(1))
            ->method('setAuthentication')
            ->with('con1', new \PHPUnit_Framework_Constraint_IsInstanceOf('OOSSH\SSH2\Authentication\Password'));

        $oossh
            ->expects($this->at(2))
            ->method('add')
            ->with('con2', new \PHPUnit_Framework_Constraint_IsInstanceOf('OOSSH\SSH2\Connection'));

        $oossh
            ->expects($this->at(3))
            ->method('setAuthentication')
            ->with('con2', new \PHPUnit_Framework_Constraint_IsInstanceOf('OOSSH\SSH2\Authentication\Publickey'));

        $this->object->load($oossh);
    }
}
