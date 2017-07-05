<?php

namespace Todo\Validator;

/**
 * Interface CheckerInterface
 * @package Todo\Validator
 */
interface CheckerInterface
{
    const DEFAULT_MIN_LENGTH = 3;
    const DEFAULT_MAX_LENGTH = 99;

    /**
     * @param string $login
     * @param int $minLength
     * @param int $maxLength
     *
     * @return string[]
     */
    public function validateString(
        $login,
        $minLength = self::DEFAULT_MIN_LENGTH,
        $maxLength = self::DEFAULT_MAX_LENGTH
    );

    /**
     * @param string $string
     *
     * @return string[]
     */
    public function validateForEmpty($string);

    /**
     * @param string $string
     * @param int $minLength
     * @param int $maxLength
     *
     * @return string[]
     */
    public function validateLength(
        $string,
        $minLength = self::DEFAULT_MIN_LENGTH,
        $maxLength = self::DEFAULT_MAX_LENGTH
    );
}
