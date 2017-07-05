<?php

namespace Todo\Tests\Session;

use Predis\Client;
use Todo\Session\Manager;
use Todo\Session\SessionInterface;
use Todo\User\Entity;
use Todo\User\UserInterface;

/**
 * Class SessionManagerTest
 * @package Todo\Tests\Session
 */
class SessionManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var SessionInterface */
    private $manager;

    public function setUp()
    {
        $redis = new Client([
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 6379,
        ]);
        $this->manager = new Manager($redis, 'sessionTest', 600);
    }

    /**
     * @param UserInterface $user
     * @param int $id
     *
     * @dataProvider dataProvider
     */
    public function testCreateSession($user, $id)
    {
        $session = $this->manager->createSession($user);
        $this->assertEquals($id, $session['userId']);
        $this->assertRegExp('/[0-9a-f]{40}/', $session['token']);
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            [(new Entity)->setId(1), 1],
            [(new Entity)->setId(15), 15]
        ];
    }

    /**
     * @param UserInterface $user
     *
     * @dataProvider dataProvider
     */
    public function testCheckExistsSession($user)
    {
        $session = $this->manager->createSession($user);
        $this->assertTrue(
            $this->manager->validateSession($session['userId'], $session['token'])
        );
    }

    /**
     * @param string $token
     * @param int $userId
     *
     * @dataProvider notExistsSessionProvider
     */
    public function notExistsSession($token, $userId)
    {
        $this->assertFalse($this->manager->validateSession($userId, $token));
    }

    /**
     * @return array
     */
    public function notExistsSessionProvider()
    {
        return [
            ['sdfsd45', 1],
            ['not valid token', 5],
            ['fake', null]
        ];
    }

    /**
     * @param UserInterface $user
     * @param int $id
     *
     * @dataProvider dataProvider
     */
    public function testDropSession($user, $id)
    {
        $session = $this->manager->createSession($user);
        $this->assertEquals($id, $session['userId']);
        $this->assertRegExp('/[0-9a-f]{40}/', $session['token']);
        $this->assertEquals($this->manager->dropSession($session['userId'], $session['token']), 1);
        $this->assertFalse($this->manager->validateSession($session['userId'], $session['token']));
    }

    /**
     * @param UserInterface $user
     * @param int $id
     *
     * @dataProvider dataProvider
     */
    public function testGetUser($user, $id)
    {
        $session = $this->manager->createSession($user);
        $this->assertEquals($id, $session['userId']);
        $this->assertEquals($user, $this->manager->getUser($session['userId'], $session['token']));
    }
}
