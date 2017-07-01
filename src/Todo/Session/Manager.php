<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 01.07.17
 * Time: 16:07
 */

namespace Todo\Session;

use Predis\Client;
use Psr\Container\ContainerInterface;
use Todo\Session\Exception\RedisClientNotSetted;
use Todo\Common\AbstractEntity;
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
     * @param ContainerInterface $container
     * @param string $path
     * @param int $ttl
     *
     * @throws RedisClientNotSetted
     */
    public function __construct(ContainerInterface $container, $path = 'loggedUsers', $ttl = 604800)
    {
        $db = $container->get('redis');
        if (!$db) {
            throw new RedisClientNotSetted;
        }
        $this->persister = $db;
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
}