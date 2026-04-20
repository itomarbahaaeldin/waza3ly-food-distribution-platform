<?php
require_once __DIR__ . '/ValidatorDecorator.php';

class FawryValidator extends ValidatorDecorator
{
    public function validate(array $data): array
    {
        $errors = parent::validate($data);

        $name = trim($data['holder_name'] ?? '');
        if ($name === '') {
            $errors['holder_name'] = 'Card holder name is required.';
        }

        $ref = trim($data['reference_number'] ?? '');
        if (!preg_match('/^[A-Za-z0-9\-]+$/', $ref)) {
            $errors['reference_number'] = 'Reference number must be alphanumeric.';
        }

        return $errors;
    }
}