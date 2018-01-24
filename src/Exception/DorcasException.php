<?php

namespace Hostville\Dorcas\Exception;


class DorcasException extends \RuntimeException
{
    /** @var array  */
    public $context;

    public function __construct(string $message = "", array $context = [])
    {
        parent::__construct($message, 0, null);
        $this->context = $context ?: [];
    }
}