<?php
require_once __DIR__ . '/ValidatorDecorator.php';

class CashValidator extends ValidatorDecorator
{
    public function validate(array $data): array
    {
        return [];
    }
}