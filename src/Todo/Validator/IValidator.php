<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 01.07.17
 * Time: 15:27
 */

namespace Todo\Validator;

interface IValidator {

    public function validateLogin($login);

    public function validatePassword($password);

    public function validateForEmpty($string);

}