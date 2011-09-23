<?php
namespace OOSSH\SSH2;


require_once '/var/www/OOSSH/src/OOSSH/SSH2/Connection.php';

/**
 * Test class for Connection.
 * Generated by PHPUnit on 2011-09-22 at 19:40:41.
 */
class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Connection
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Connection(TEST_HOST);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    protected function connectAndAuth()
    {
        $this->object->connect();
        $this->object->authenticate(new \OOSSH\SSH2\Authentication\PasswordAuthentication(TEST_USER, TEST_PASSWORD));
    }

    /**
     *
     */
    public function testConnect()
    {
        $this->object->connect();
        $this->assertTrue($this->object->isConnected());
    }

    /**
     * @todo Implement testCheck().
     */
    public function testCheck()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @dataProvider providerAuthenticate
     * @depends testConnect
     */
    public function testAuthenticate($provider)
    {
        $this->object->connect();
        $this->object->authenticate($provider);
        $this->assertTrue($this->object->isAuthenticated());
    }

    /**
     * @todo Implement testExec().
     */
    public function testExec()
    {
        $this->connectAndAuth();

        $that = $this;

        $this->object->exec('uname -a', function($stdio, $stderr) use($that) { $that->assertInternalType('string', $stdio); });
    }

    /**
     * @todo Implement testBegin().
     */
    public function testBlock()
    {
        $this->connectAndAuth();

        $that = $this;

        $this->object->begin()
            ->exec('pwd')
        ->end(function($stdio, $stderr) use($that) { $that->assertInternalType('string', $stdio); });
    }


    public function providerAuthenticate()
    {
        return array(
            array(new \OOSSH\SSH2\Authentication\PasswordAuthentication(TEST_USER, TEST_PASSWORD)),
            array(new \OOSSH\SSH2\Authentication\PublicKeyAuthentication(TEST_USER, TEST_PUBKEY_FILE, TEST_PRIVKEY_FILE)),
        );
    }
}
?>
