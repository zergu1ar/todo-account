<?php

namespace Todo\Session;

use Todo\Crypt\Coder;
use Predis\ClientInterface;
use Zergular\Common\EntityInterface;

/**
 * Class Manager
 * @package Todo\Session
 */
class Manager implements SessionInterface
{
    /** @var ClientInterface */
    private $persister;
    /** @var string */
    private $path;
    /** @var */
    private $ttl;

    /**
     * Manager constructor.
     * @param ClientInterface $redis
     * @param string $path
     * @param int $ttl
     */
    public function __construct(ClientInterface $redis, $path = 'loggedUsers', $ttl = 604800)
    {
        $this->persister = $redis;
        $this->path = $path;
        $this->ttl = $ttl;
    }

    /**
     * @inheritdoc
     */
    public function createSession(EntityInterface $user)
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
     * @inheritdoc
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
     * @inheritdoc
     */
    public function validateSession($userId, $token)
    {
        return !is_null(
            $this->getUser($userId, $token)
        );
    }

    /**
     * @inheritdoc
     */
    public function dropSession($userId, $token)
    {
        return $this->persister->del([$this->getKeyName($userId, $token)]);
    }
}
