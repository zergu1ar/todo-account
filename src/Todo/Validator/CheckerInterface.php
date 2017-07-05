<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 01.07.17
 * Time: 15:27
 */

namespace Todo\Validator;

interface CheckerInterface {

    public function validateString($login);

    public function validateForEmpty($string);

    public function validateLength($string, $minLength, $maxLength);
}