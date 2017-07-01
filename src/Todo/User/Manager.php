<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 01.07.17
 * Time: 12:14
 */

namespace Todo\User;

use Todo\Common\AbstractEntity;
use Todo\Common\AbstractManager;

class Manager extends AbstractManager
{
    protected $tableName = 'users';
    protected $entityName = '\\Todo\\User\\Entity';

    /**
     * @param string $login
     * @return int
     */
    public function isExists($login)
    {
        return $this->getCounts(['login' => $login]);
    }

    /**
     * @param string $login
     * @param string $pwd
     * @return AbstractEntity
     */
    public function getUserByLoginAndPwd($login, $pwd)
    {
        return $this->getOne([
            'login' => $login,
            'password' => $pwd
        ]);
    }
}