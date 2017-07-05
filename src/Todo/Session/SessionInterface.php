<?php

namespace Todo\Session;

use Zergular\Common\EntityInterface;

/**
 * Interface SessionInterface
 * @package Todo\Session
 */
interface SessionInterface
{
    /**
     * @param EntityInterface $user
     *
     * @return array
     */
    public function createSession(EntityInterface $user);

    /**
     * @param int $userId
     * @param string $token
     *
     * @return EntityInterface
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
