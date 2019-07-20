<?php

namespace mradang\LumenCustomField;

class CustomFieldException extends \Exception {

    public function __construct($msg = '') {
        parent::__construct($msg);
    }

}
