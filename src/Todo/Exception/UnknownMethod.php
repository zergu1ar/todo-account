<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 01.07.17
 * Time: 13:11
 */

namespace Todo\Exception;

class UnknownMethod extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            'Unknown method ' . $message,
            $code,
            $previous
        );
    }
}