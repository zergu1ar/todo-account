<?php

namespace Todo\User;

use Zergular\Common\ManagerInterface;

/**
 * Interface UserManagerInterface
 * @package Todo\User
 */
interface UserManagerInterface extends ManagerInterface
{
    /**
     * @param string $login
     *
     * @return int
     */
    public function isExists($login);

    /**
     * @param string $login
     * @param string $pwd
     *
     * @return UserManagerInterface
     */
    public function getUserByLoginAndPwd($login, $pwd);
}
