<?php

require_once __DIR__ . '/ValidatorDecorator.php';

class VolunteerInfoValidator extends ValidatorDecorator
{
    public function validate(array $data): array
    {
        $errors = parent::validate($data);

        if (empty($data['transportation_type_id'])) {
            $errors['transportation_type_id'] = 'Please select a transportation type';
        }
        if (empty($data['service_radius'])) {
            $errors['service_radius'] = 'Service radius is required';
        } elseif (!filter_var($data['service_radius'], FILTER_VALIDATE_INT)) {
            $errors['service_radius'] = 'Service radius must be a number';
        } elseif ($data['service_radius'] < 1 || $data['service_radius'] > 50) {
            $errors['service_radius'] = 'Service radius must be between 1 and 50 km';
        }
        if (empty($data['availability']) || !is_array($data['availability'])) {
            $errors['availability'] = 'Please select at least one day of availability';
        }
        if (empty($data['time_slot_id'])) {
            $errors['time_slot_id'] = 'Please select a preferred time slot';
        }
        if (isset($data['experience']) && strlen($data['experience']) > 100) {
            $errors['experience'] = 'Experience must be at most 100 characters';
        }

        return $errors;
    }
}