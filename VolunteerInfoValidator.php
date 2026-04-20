<?php

require_once __DIR__ . '/ValidatorDecorator.php';
require_once __DIR__ . '/../models/UsersModel.php';

class PersonalInfoValidator extends ValidatorDecorator
{
    private $userModel;

    public function __construct(ValidatorInterface $next, $db)
    {
        parent::__construct($next);
        $this->userModel = new UsersModel($db);
    }

    public function validate(array $data): array
    {
        $errors = parent::validate($data);

        // First name
        if (empty($data['first_name'])) {
            $errors['first_name'] = 'First name is required';
        } elseif (strlen($data['first_name']) < 3 || strlen($data['first_name']) > 20) {
            $errors['first_name'] = 'First name must be between 3 and 20 characters';
        } elseif (!preg_match('/^[a-zA-Z\s]+$/', $data['first_name'])) {
            $errors['first_name'] = 'First name can only contain letters and spaces';
        }

        // Last name
        if (empty($data['last_name'])) {
            $errors['last_name'] = 'Last name is required';
        } elseif (strlen($data['last_name']) < 3 || strlen($data['last_name']) > 20) {
            $errors['last_name'] = 'Last name must be between 3 and 20 characters';
        } elseif (!preg_match('/^[a-zA-Z\s]+$/', $data['last_name'])) {
            $errors['last_name'] = 'Last name can only contain letters and spaces';
        }

        // Email
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address';
        } elseif (strlen($data['email']) > 30) {
            $errors['email'] = 'Email must be less than 30 characters';
        } elseif ($this->userModel->emailExists($data['email'])) {
            $errors['email'] = 'This email is already registered';
        }

        // Phone
        if (empty($data['phone'])) {
            $errors['phone'] = 'Phone number is required';
        } elseif (strlen($data['phone']) !== 11) {
            $errors['phone'] = 'Phone number must be 11 digits';
        } elseif (!preg_match('/^[0-9]+$/', $data['phone'])) {
            $errors['phone'] = 'Phone number can only contain digits';
        } elseif ($this->userModel->phoneExists($data['phone'])) {
            $errors['phone'] = 'This phone number is already registered';
        }

        return $errors;
    }
}