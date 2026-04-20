<?php

require_once __DIR__ . '/../utils/BaseValidator.php';
require_once __DIR__ . '/../utils/VisaValidator.php';

class VisaStrategy
{
    private $validator;

    public function __construct()
    {
        $this->validator = new VisaValidator(new BaseValidator());
    }

    /**
     * @param array $rawValues  
     * @return true|array      
     */
    public function process(array $rawValues)
    {
        $errors = $this->validator->validate($rawValues);
        return $errors ?: true;
    }
}