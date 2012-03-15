<?php

namespace Rapidweb {
    class ValidationException extends \Exception {
        public $errors;

        public function __construct($message, $errors) {
            $this->errors = $errors;
            parent::__construct($message);
        }
    }
}
