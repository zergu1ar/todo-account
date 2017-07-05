<?php

namespace Todo\User;

use Zergular\Common\AbstractManager;

/**
 * Class Manager
 * @package Todo\User
 */
class Manager extends AbstractManager implements UserManagerInterface
{
    /** @var string */
    protected $tableName = 'users';
    /** @var string */
    protected $entityName = '\\Todo\\User\\Entity';

    /**
     * @inheritdoc
     */
    public function isExists($login)
    {
        return $this->getCounts(['login' => $login]);
    }

    /**
     * @inheritdoc
     */
    public function getUserByLoginAndPwd($login, $pwd)
    {
        return $this->getOne([
            'login' => $login,
            'password' => $pwd
        ]);
    }
}
