<?php
require_once __DIR__ . '/ValidatorDecorator.php';

class VisaValidator extends ValidatorDecorator
{
    public function validate(array $data): array
    {
        $errors = parent::validate($data);

        // card number: digits only, length 13–19
        $cn = $data['card_number'] ?? '';
        if (!preg_match('/^\d{13,19}$/', $cn)) {
            $errors['card_number'] = 'Card number must be 13-19 digits.';
        }

        // CVV: 3 or 4 digits
        $cvv = $data['cvv'] ?? '';
        if (!preg_match('/^\d{3,4}$/', $cvv)) {
            $errors['cvv'] = 'CVV must be 3 or 4 digits.';
        }

        // expiration_month: 1–12
        $m = filter_var($data['expiration_month'] ?? null, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 12]
        ]);
        if ($m === false) {
            $errors['expiration_month'] = 'Invalid expiration month.';
        }

        // expiration_year: current ≤ year ≤ current+10
        $y = filter_var($data['expiration_year'] ?? null, FILTER_VALIDATE_INT);
        $nowY = (int)date('Y');
        if ($y === false || $y < $nowY || $y > $nowY + 10) {
            $errors['expiration_year'] = "Expiration year must be between $nowY and " . ($nowY + 10) . '.';
        }

        return $errors;
    }
}