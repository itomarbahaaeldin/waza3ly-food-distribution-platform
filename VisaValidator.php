<?php

require_once __DIR__ . '/../utils/ValidatorDecorator.php';

class PersonalInfoUpdateValidator extends ValidatorDecorator {
    private $db;
    private $userModel;
    private $currentUser;

    public function __construct($validator, $db, $currentUser) {
        parent::__construct($validator);
        $this->db = $db;
        $this->currentUser = $currentUser;
        require_once __DIR__ . '/../models/UsersModel.php';
        $this->userModel = new UsersModel($this->db);
    }

    public function validate(array $data): array
    {
        // First, call the wrapped validator's validate method
        $errors = parent::validate($data);

        // First name validation
        if (empty($data['first_name'])) {
            $errors['first_name'] = 'First name is required';
        } elseif (strlen($data['first_name']) < 3 || strlen($data['first_name']) > 20) {
            $errors['first_name'] = 'First name must be between 3 and 20 characters';
        } elseif (!preg_match('/^[a-zA-Z\s]+$/', $data['first_name'])) {
            $errors['first_name'] = 'First name can only contain letters and spaces';
        }

        // Last name validation
        if (empty($data['last_name'])) {
            $errors['last_name'] = 'Last name is required';
        } elseif (strlen($data['last_name']) < 3 || strlen($data['last_name']) > 20) {
            $errors['last_name'] = 'Last name must be between 3 and 20 characters';
        } elseif (!preg_match('/^[a-zA-Z\s]+$/', $data['last_name'])) {
            $errors['last_name'] = 'Last name can only contain letters and spaces';
        }

        // Email validation
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address';
        } elseif (strlen($data['email']) > 30) {
            $errors['email'] = 'Email must be less than 30 characters';
        } elseif ($data['email'] !== $this->currentUser['email']) {
            // Only check email uniqueness if it has changed
            if ($this->userModel->emailExists($data['email'])) {
                $errors['email'] = 'This email is already registered';
            }
        }

        // Phone validation
        if (empty($data['phone'])) {
            $errors['phone'] = 'Phone number is required';
        } elseif (strlen($data['phone']) !== 11) {
            $errors['phone'] = 'Phone number must be 11 digits';
        } elseif (!preg_match('/^[0-9]+$/', $data['phone'])) {
            $errors['phone'] = 'Phone number can only contain digits';
        } elseif ($data['phone'] !== $this->currentUser['phone']) {
            // Only check phone uniqueness if it has changed
            if ($this->userModel->phoneExists($data['phone'])) {
                $errors['phone'] = 'This phone number is already registered';
            }
        }

        // Address validation
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