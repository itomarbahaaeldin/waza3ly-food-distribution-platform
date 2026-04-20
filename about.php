<?php

require_once __DIR__ . '/ValidatorInterface.php';
require_once __DIR__ . '/BaseValidator.php';

class RequestValidator implements ValidatorInterface {
    private $next;

    public function __construct(ValidatorInterface $next) {
        $this->next = $next;
    }

    public function validate(array $data): array {
        $errors = [];

        if ($data['name'] === '') {
            $errors['name'] = 'Request name is required';
        } elseif (strlen($data['name']) > 40) {
            $errors['name'] = 'Request name must be at most 40 characters';
        }

        // description 
        if (isset($data['description'])) {
            $desc = trim($data['description']);
            if ($desc === '') {
                $errors['description'] = 'Description cannot be empty';
            } elseif (strlen($desc) > 500) {
                $errors['description'] = 'Description must be at most 500 characters';
            }
        }

        // street_address
        if (empty(trim($data['street_address'] ?? ''))) {
            $errors['street_address'] = 'Street address is required';
        } else {
            $len = strlen($data['street_address']);
            if ($len < 10 || $len > 25) {
                $errors['street_address'] = 'Street address must be between 10 and 25 characters';
            }
        }

        // governorate_id (dropdown)
        if (empty($data['governorate_id'])) {
            $errors['governorate_id'] = 'Please select a governorate';
        }

        // city_id (dropdown)
        if (empty($data['city_id'])) {
            $errors['city_id'] = 'Please select a city';
        }

        // scheduled_pickup
        if (empty($data['scheduled_pickup'])) {
            $errors['scheduled_pickup'] = 'Scheduled pickup is required';
        } else {
            $dt = \DateTime::createFromFormat('Y-m-d\TH:i', $data['scheduled_pickup']);
            if (!$dt) {
                $errors['scheduled_pickup'] = 'Invalid date/time format';
            } elseif ($dt < new \DateTime()) {
                $errors['scheduled_pickup'] = 'Scheduled pickup must be in the future';
            }
        }

        // food_items array
        if (empty($data['food_items']) || !is_array($data['food_items'])) {
            $errors['food_items'] = 'At least one food item is required';
        } else {
            foreach ($data['food_items'] as $i => $item) {
                $prefix = "food_items[$i]";

                // category_id (dropdown)
                if (empty($item['category_id'])) {
                    $errors["$prefix[category_id]"] = 'Please select a category';
                }

                // food_name (max 50 chars)
                $name = trim($item['food_name'] ?? '');
                if ($name === '') {
                    $errors["$prefix[food_name]"] = 'Food name is required';
                } elseif (strlen($name) > 50) {
                    $errors["$prefix[food_name]"] = 'Food name must be at most 50 characters';
                }

                // quantity
                if (!isset($item['quantity']) || !is_numeric($item['quantity']) || $item['quantity'] <= 0) {
                    $errors["$prefix[quantity]"] = 'Quantity must be a number greater than 0';
                }

                // unit (max 15 chars)
                $unit = trim($item['unit'] ?? '');
                if ($unit === '') {
                    $errors["$prefix[unit]"] = 'Unit is required';
                } elseif (strlen($unit) > 15) {
                    $errors["$prefix[unit]"] = 'Unit must be at most 15 characters';
                }
            }
        }

        return $errors;
    }
}