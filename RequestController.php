<?php
require_once __DIR__ . '/PaymentStrategy.php';
require_once __DIR__ . '/../utils/BaseValidator.php';
require_once __DIR__ . '/../utils/FawryValidator.php';

class FawryStrategy implements PaymentStrategy
{
    private $validator;

    public function __construct()
    {
        $this->validator = new FawryValidator(new BaseValidator());
    }

    /**
     * @param array $rawValues
     * @return true|array  true on success, or array of errors
     */
    public function process(array $rawValues)
    {
        $errors = $this->validator->validate($rawValues);
        return $errors ?: true;
    }
}