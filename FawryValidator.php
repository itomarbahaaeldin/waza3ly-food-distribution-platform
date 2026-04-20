<?php

require_once __DIR__ . '/ValidatorDecorator.php';

class AccountInfoValidator extends ValidatorDecorator
{
    public function validate(array $data): array
    {
        $errors = parent::validate($data);

        if (empty($data['username'])) {
            $errors['username'] = 'Username is required';
        } elseif (strlen($data['username']) < 5 || strlen($data['username']) > 20) {
            $errors['username'] = 'Username must be between 5 and 20 characters';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $data['username'])) {
            $errors['username'] = 'Username can only contain letters, numbers, and underscores';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters long';
        }

        if (empty($data['confirm_password'])) {
            $errors['confirm_password'] = 'Please confirm your password';
        } elseif ($data['password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = 'Passwords do not match';
        }

        return $errors;
    }
}