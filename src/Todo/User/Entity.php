<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 01.07.17
 * Time: 12:13
 */

namespace Todo\User;

use Zergular\Common\AbstractEntity;

class Entity extends AbstractEntity
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
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return $this
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }


}