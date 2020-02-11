<?php

namespace mradang\LaravelCustomField\Exceptions;

class CustomFieldException extends \Exception
{
    public function __construct($msg = '')
    {
        parent::__construct($msg);
    }
}
