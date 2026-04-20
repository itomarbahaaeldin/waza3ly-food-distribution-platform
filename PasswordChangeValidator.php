<?php

require_once __DIR__ . '/ValidatorDecorator.php';

class AddressInfoValidator extends ValidatorDecorator
{
    public function validate(array $data): array
    {
        $errors = parent::validate($data);

        if (empty($data['address'])) {
            $errors['address'] = 'Street address is required';
        } elseif (strlen($data['address']) < 10 || strlen($data['address']) > 25) {
            $errors['address'] = 'Street address must be between 10 and 25 characters';
        }
        if (empty($data['governorate_id'])) {
            $errors['governorate_id'] = 'Please select a governorate';
        }
        if (empty($data['city_id'])) {
            $errors['city_id'] = 'Please select a city/district';
        }

        return $errors;
    }
}