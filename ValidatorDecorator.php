<?php

require_once __DIR__ . '/ValidatorDecorator.php';

class PasswordChangeValidator extends ValidatorDecorator
{
    private $currentPasswordHash;

    public function __construct(ValidatorInterface $next, $currentPasswordHash)
    {
        parent::__construct($next);
        $this->currentPasswordHash = $currentPasswordHash;
    }

    public function validate(array $data): array
    {
        $errors = parent::validate($data);

        if (empty($data['current_password'])) {
            $errors['current_password'] = 'Current password is required';
        } elseif (!password_verify($data['current_password'], $this->currentPasswordHash)) {
            $errors['current_password'] = 'Current password is incorrect';
        }

        if (empty($data['new_password'])) {
            $errors['new_password'] = 'New password is required';
        } elseif (strlen($data['new_password']) < 8) {
            $errors['new_password'] = 'New password must be at least 8 characters long';
        } elseif ($data['current_password'] === $data['new_password']) {
            $errors['new_password'] = 'New password must be different from current password';
        }

        if (empty($data['confirm_password'])) {
            $errors['confirm_password'] = 'Please confirm your new password';
        } elseif ($data['new_password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = 'Passwords do not match';
        }

        return $errors;
    }
}