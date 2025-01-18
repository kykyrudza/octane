<?php

namespace Kernel\Application\Validation\Rules;

/**
 * Class NumberRule
 *
 * Validates if the value contains at least one number.
 *
 * @package Kernel\Application\Validation\Rules
 */
class NumberRule implements ValidationRule
{
    /**
     * Validate if the value contains at least one number.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value of the field.
     * @param array $parameters Optional parameters (unused for this rule).
     *
     * @return bool True if the value contains a number, false otherwise.
     */
    public function validate(string $field, mixed $value, array $parameters = []): bool
    {
        return is_string($value) && preg_match('/\d/', $value);
    }

    /**
     * Get the error message if the value does not contain a number.
     *
     * @param string $field The name of the field being validated.
     * @param array $parameters Optional parameters (unused for this rule).
     *
     * @return string The error message for not containing a number.
     */
    public function getMessage(string $field, array $parameters = []): string
    {
        return "The {$field} must contain at least one number.";
    }
}
