<?php

require_once __DIR__ . '/ValidatorInterface.php';

abstract class ValidatorDecorator implements ValidatorInterface
{
    protected $next;

    public function __construct(ValidatorInterface $next)
    {
        $this->next = $next;
    }

    public function validate(array $data): array
    {
        // start with whatever previous validators gave us
        return $this->next->validate($data);
    }
}