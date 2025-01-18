<?php

namespace Kernel\Application\Validation;

use Kernel\Application\Validation\Validator\Validator;

/**
 * Class Validation
 *
 * This class handles the validation of input data based on the specified rules. It uses the Validator class to
 * perform the actual validation and return any errors that occur during the validation process.
 *
 * @package Kernel\Application\Validation
 */
class Validation
{
    /**
     * @var array An array to hold the validation error messages
     */
    protected array $errors = [];

    /**
     * Validation constructor.
     *
     * Initializes the validator instance and sets the rules for data validation.
     *
     * @param Validator $validator The Validator instance used for validation
     */
    public function __construct(
        private readonly Validator $validator
    ) {}

    /**
     * Validates the input data against the specified rules.
     *
     * This method uses the Validator class to perform validation, setting the validation rules and input data,
     * and returns any validation errors encountered.
     *
     * @param array $rules The validation rules to apply to the data
     * @param array $data The data to validate
     *
     * @return array An array of validation error messages, if any
     */
    public function validate(array $rules, array $data): array
    {
        $this->validator->setRules($rules);
        $this->validator->setData($data);
        $this->validator->validate();

        if ($this->validator->hasErrors()) {
            $this->errors = $this->validator->errors();
        }

        return $this->errors;
    }

    /**
     * Retrieves the validation error messages.
     *
     * This method returns the validation errors that occurred during the last validation process.
     *
     * @return array An array of validation error messages
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
