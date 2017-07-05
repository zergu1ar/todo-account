<?php

namespace Zergular\Todo\Validator;

/**
 * Class Checker
 * @package Zergular\Todo\Validator
 */
class Checker implements CheckerInterface
{
    /**
     * @inheritdoc
     */
    public function validateString($login, $minLength = self::DEFAULT_MIN_LENGTH, $maxLength = self::DEFAULT_MAX_LENGTH)
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
     * @inheritdoc
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
     * @inheritdoc
     */
    public function validateLength(
        $string,
        $minLength = self::DEFAULT_MIN_LENGTH,
        $maxLength = self::DEFAULT_MAX_LENGTH
    ) {
        $string = trim($string);
        $len = mb_strlen($string);
        return [
            ($len >= $maxLength || $len <= $minLength)
                ? sprintf('Field must be from %d to %d symbols', $minLength, $maxLength)
                : ''
        ];
    }
}
