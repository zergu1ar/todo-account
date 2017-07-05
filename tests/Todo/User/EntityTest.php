<?php

namespace Zergular\Todo\Tests\User;

use Zergular\Todo\User\Entity;

/**
 * Class EntityTest
 * @package Zergular\Todo\Tests\User
 */
class EntityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $login
     * @param string $password
     * @param array $expectedArray
     *
     * @dataProvider dataProvider
     */
    public function testToArray($login, $password, $expectedArray)
    {
        $entity = new Entity($login, $password);
        $this->assertEquals($login, $entity->getLogin());
        $this->assertEquals($password, $entity->getPassword());
        $this->assertEquals($entity->toArray([]), $expectedArray);
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            [
                'test', 'testpwd',
                [
                    'login' => 'test',
                    'password' => 'testpwd',
                    'id' => NULL,
                    'created' => NULL,
                    'updated' => NULL
                ]
            ],
            [
                'login', 'password',
                [
                    'login' => 'login',
                    'password' => 'password',
                    'id' => NULL,
                    'created' => NULL,
                    'updated' => NULL
                ]
            ]
        ];
    }
}
