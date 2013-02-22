<?php

namespace OOSSH\Tests;

use OOSSH\OOSSH;

/**
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class OOSSHTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OOSSH
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new OOSSH;
    }

    public function testCreate()
    {
        $this->assertInstanceOf('OOSSH\OOSSH', OOSSH::create());
    }

    public function testGet()
    {
        $connection = $this->getMock('OOSSH\ConnectionInterface');
        $connection->expects($this->never())->method('connect');
        $connection->expects($this->never())->method('authenticate');

        $connection2 = $this->getMock('OOSSH\ConnectionInterface');
        $connection2->expects($this->once())->method('connect');
        $connection2->expects($this->never())->method('authenticate');
        $connection2->expects($this->at(0))->method('isConnected')->will($this->returnValue(false));
        $connection2->expects($this->at(2))->method('isConnected')->will($this->returnValue(true));

        $connection3 = $this->getMock('OOSSH\ConnectionInterface');
        $connection3->expects($this->once())->method('connect');
        $connection3->expects($this->once())->method('authenticate');
        $connection3->expects($this->at(0))->method('isConnected')->will($this->returnValue(false));
        $connection3->expects($this->at(4))->method('isConnected')->will($this->returnValue(true));
        $connection3->expects($this->at(2))->method('isAuthenticated')->will($this->returnValue(false));
        $connection3->expects($this->at(5))->method('isAuthenticated')->will($this->returnValue(true));

        $authentication = $this->getMock('OOSSH\Authentication\AuthenticationInterface');

        $this->object->add('con1', $connection);
        $this->object->add('con2', $connection2);
        $this->object->add('con3', $connection3);
        $this->object->setAuthentication('con3', $authentication);

        $this->object->get('con1', false, false);
        $this->object->get('con1', false, false);
        $this->object->get('con2', true, false);
        $this->object->get('con2', true, false);
        $this->object->get('con3');
        $this->object->get('con3');
    }
}
