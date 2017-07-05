<?php

namespace Zergular\Todo\User;

use Zergular\Common\ManagerInterface;

/**
 * Interface UserManagerInterface
 * @package Zergular\Todo\User
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
     * @return UserInterface
     */
    public function getUserByLoginAndPwd($login, $pwd);
}
