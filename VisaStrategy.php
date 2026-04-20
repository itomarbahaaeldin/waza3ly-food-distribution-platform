<?php

interface PaymentStrategy
{
    /**
     * Validate & (optionally) process this payment’s raw option values.
     *
     * @param array $rawValues   [ option_id => submitted_value, … ]
     * @return true|array        true on success, or [ 'field' => 'error message', … ]
     */
    public function process(array $rawValues);
}