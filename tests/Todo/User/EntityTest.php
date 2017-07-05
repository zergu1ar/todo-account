<?php
/**
 * Created by PhpStorm.
 * User: devel
 * Date: 05.07.17
 * Time: 12:16
 */

namespace Tests\Todo\User;

use Todo\User\Entity;

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

        var_dump($entity->toArray([]));
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