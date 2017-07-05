<?php

namespace Zergular\Todo\User;

use Zergular\Common\EntityInterface;

/**
 * Interface UserInterface
 * @package Zergular\Todo\User
 */
interface UserInterface extends EntityInterface
{
    /**
     * @return string
     */
    public function getLogin();

    /**
     * @param string $login
     *
     * @return UserInterface
     */
    public function setLogin($login);

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @param string $password
     *
     * @return UserInterface
     */
    public function setPassword($password);
}
