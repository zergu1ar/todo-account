<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 01.07.17
 * Time: 16:07
 */

namespace Todo\Session;

use Predis\Client;
use Zergular\Common\AbstractEntity;
use Todo\Crypt\Coder;

class Manager
{
    /** @var Client */
    private $persister;
    /** @var string */
    private $path;
    /** @var */
    private $ttl;

    /**
     * Manager constructor.
     * @param Client $redis
     * @param string $path
     * @param int $ttl
     */
    public function __construct(Client $redis, $path = 'loggedUsers', $ttl = 604800)
    {
        $this->persister = $redis;
        $this->path = $path;
        $this->ttl = $ttl;
    }

    /**
     * @param AbstractEntity $user
     *
     * @return array
     */
    public function createSession(AbstractEntity $user)
    {
        $token = Coder::createToken($user->getId());
        $this->persister->setex(
            $this->getKeyName($user->getId(), $token),
            $this->ttl,
            serialize($user)
        );
        return [
            'token' => $token,
            'userId' => $user->getId()
        ];
    }

    /**
     * @param int $id
     * @param string $token
     *
     * @return string
     */
    private function getKeyName($id, $token)
    {
        return sprintf(
            '%s:%d:%s',
            $this->path,
            $id,
            $token
        );
    }

    /**
     * @param int $userId
     * @param string $token
     *
     * @return AbstractEntity
     */
    public function getUser($userId, $token)
    {
        $user = $this->persister->get(
            $this->getKeyName($userId, $token)
        );
        return $user
            ? unserialize($user)
            : NULL;
    }

    /**
     * @param int $userId
     * @param string $token
     *
     * @return bool
     */
    public function validateSession($userId, $token)
    {
        return !is_null(
            $this->getUser($userId, $token)
        );
    }

    /**
     * @param int $userId
     * @param string $token
     *
     * @return int
     */
    public function dropSession($userId, $token)
    {
        return $this->persister->del([$this->getKeyName($userId, $token)]);
    }
}