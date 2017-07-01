<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 01.07.17
 * Time: 12:34
 */

namespace Todo\Exception;

use Throwable;

class PersisterNotSetted extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            $message
                ? $message
                : 'Persister not setted in DI Container',
            $code,
            $previous
        );
    }
}