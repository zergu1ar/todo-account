<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 01.07.17
 * Time: 15:29
 */

namespace Todo\Validator;

use Todo\Validator\IValidator;

class Checker implements IValidator
{
    /**
     * @param string $login
     * @param int $minLength
     * @param int $maxLength
     *
     * @return string[]
     */
    public function validateLogin($login, $minLength = 3, $maxLength = 99)
    {
        return array_values(
            array_filter(
                array_merge(
                    $this->validateForEmpty($login),
                    $this->validateLength($login, $minLength, $maxLength)
                )
            )
        );
    }

    /**
     * @param string $password
     * @param int $minLength
     * @param int $maxLength
     *
     * @return string[]
     */
    public function validatePassword($password, $minLength = 3, $maxLength = 99)
    {
        return array_values(
            array_filter(
                array_merge(
                    $this->validateForEmpty($password),
                    $this->validateLength($password, $minLength, $maxLength)
                )
            )
        );
    }

    /**
     * @param string $string
     *
     * @return string[]
     */
    public function validateForEmpty($string)
    {
        return [
            empty(trim($string))
                ? 'Field can not be empty'
                : ''
        ];
    }

    /**
     * @param string $string
     * @param int $minLength
     * @param int $maxLength
     *
     * @return string[]
     */
    public function validateLength($string, $minLength = 3, $maxLength = 6)
    {
        $string = trim($string);
        $len = mb_strlen($string);
        return [
            ($len > $maxLength || $len < $minLength)
                ? sprintf('Field must be from %d to %d symbols', $minLength, $maxLength)
                : ''
        ];
    }
}

