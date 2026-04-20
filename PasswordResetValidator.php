<?php

require_once __DIR__ . '/ValidatorInterface.php';

class BaseValidator implements ValidatorInterface
{
    public function validate(array $data): array
    {
        // no rules here
        return [];
    }
}