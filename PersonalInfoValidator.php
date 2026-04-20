<?php
// File: app/utils/DonorInfoValidator.php

require_once __DIR__ . '/ValidatorDecorator.php';

class DonorInfoValidator extends ValidatorDecorator
{
    public function validate(array $data): array
    {
        // start with any errors from the previous decorators (personal, address, account)
        $errors = parent::validate($data);

        // donation_frequency_id: must be present 
        if (empty($data['donation_frequency_id'])) {
            $errors['donation_frequency_id'] = 'Please select how often you expect to donate';
        }

        // typical_quantity: must be present, integer between 1 and 1000
        if (!isset($data['typical_quantity']) || $data['typical_quantity'] === '') {
            $errors['typical_quantity'] = 'Typical quantity is required';
        } elseif (!filter_var($data['typical_quantity'], FILTER_VALIDATE_INT)) {
            $errors['typical_quantity'] = 'Quantity must be a number';
        } elseif ($data['typical_quantity'] < 1 || $data['typical_quantity'] > 1000) {
            $errors['typical_quantity'] = 'Quantity must be between 1 and 1000 servings';
        }

        // pickup_time: must be present
        if (empty($data['pickup_time'])) {
            $errors['pickup_time'] = 'Please select your preferred pickup time';
        }

        // additional_info: optional, but if provided max 100 chars
        if (isset($data['additional_info']) && strlen($data['additional_info']) > 100) {
            $errors['additional_info'] = 'Additional info must be at most 100 characters';
        }

        return $errors;
    }
}