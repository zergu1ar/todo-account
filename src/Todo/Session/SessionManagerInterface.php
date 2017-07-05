<?php

namespace Zergular\Todo\Session;

use Zergular\Todo\User\UserInterface;

/**
 * Interface SessionInterface
 * @package Zergular\Todo\Session
 */
interface SessionManagerInterface
{
    /**
     * @param UserInterface $user
     *
     * @return array
     */
    public function createSession(UserInterface $user);

    /**
     * @param int $userId
     * @param string $token
     *
     * @return UserInterface
     */
    public function getUser($userId, $token);

    /**
     * @param int $userId
     * @param string $token
     *
     * @return bool
     */
    public function validateSession($userId, $token);

    /**
     * @param int $userId
     * @param string $token
     *
     * @return int
     */
    public function dropSession($userId, $token);
}
