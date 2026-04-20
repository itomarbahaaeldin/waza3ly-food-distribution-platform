<?php

interface ValidatorInterface
{
    /**
     * @param array $data
     * @return array Field => error message
     */
    public function validate(array $data): array;
}