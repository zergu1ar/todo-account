<?php

namespace Zergular\Todo\User;

use Zergular\Common\AbstractEntity;

/**
 * Class Entity
 * @package Zergular\Todo\User
 */
class Entity extends AbstractEntity implements UserInterface
{

    /** @var string */
    protected $login;
    /** @var string */
    protected $password;

    /**
     * Entity constructor.
     * @param string $login
     * @param string $password
     */
    public function __construct($login = '', $password = '')
    {
        $this->setLogin($login)
            ->setPassword($password);
    }

    /**
     * @inheritdoc
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @inheritdoc
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritdoc
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
}
