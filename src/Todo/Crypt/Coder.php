<?php

namespace Zergular\Todo\Crypt;

/**
 * Class Coder
 * @package Zergular\Todo\Crypt
 */
class Coder
{
    /**
     * @param string $string
     *
     * @return string
     */
    public static function createToken($string)
    {
        return sha1(md5($string . time() . rand(0, 999)));
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function encrypt($string)
    {
        return sha1(md5($string));
    }
}
