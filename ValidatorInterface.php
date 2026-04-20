<?php

require_once __DIR__ . '/ValidatorDecorator.php';

class PasswordResetValidator extends ValidatorDecorator
{
    private $currentPasswordHash;

    public function __construct(ValidatorInterface $next, $currentPasswordHash)
    {
        parent::__construct($next);
        $this->currentPasswordHash = $currentPasswordHash;
    }

    public function validate(array $data): array
    {
        // Start with any errors from previous validators (if any)
        $errors = parent::validate($data);

        // Validate new password
        if (empty($data['new_password'])) {
            $errors['new_password'] = 'New password is required';
        } elseif (strlen($data['new_password']) < 8) {
            $errors['new_password'] = 'New password must be at least 8 characters long';
        } elseif (password_verify($data['new_password'], $this->currentPasswordHash)) {
            $errors['new_password'] = 'New password must be different from your current password';
        }

        // Validate confirmation
        if (empty($data['confirm_password'])) {
            $errors['confirm_password'] = 'Please confirm your new password';
        } elseif ($data['new_password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = 'Passwords do not match';
        }

        return $errors;
    }
}