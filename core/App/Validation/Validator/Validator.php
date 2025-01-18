<?php

namespace Kernel\Application\Validation\Validator;

use Kernel\Application\Validation\RuleFactory;

/**
 * Class Validator
 *
 * This class is responsible for validating data based on a set of rules. It checks the data against the specified rules,
 * and if any validation fails, it collects error messages related to the fields that failed.
 *
 * @package Kernel\Application\Validation\Validator
 */
class Validator
{
    /**
     * @var array List of validation errors that occurred during the validation process.
     */
    protected array $errors = [];

    /**
     * @var array The validation rules to be applied on the data.
     */
    protected array $rules = [];

    /**
     * @var array The data to be validated.
     */
    protected array $data = [];

    /**
     * Set the validation rules.
     *
     * This method assigns the rules that will be used for validation. The rules are expected to be an associative array
     * where the key is the field name and the value is an array of rules to apply to that field.
     *
     * @param array $rules An associative array of field names and their validation rules.
     */
    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    /**
     * Set the data to be validated.
     *
     * This method assigns the data that will be checked against the validation rules. The data should be an associative
     * array where the key is the field name and the value is the value to be validated.
     *
     * @param array $data The data to be validated.
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * Validate the data against the rules.
     *
     * This method goes through each rule for each field and validates the data. If a validation rule fails,
     * the corresponding error message is stored.
     */
    public function validate(): void
    {
        foreach ($this->rules as $field => $fieldRules) {
            $value = $this->data[$field] ?? null;

            foreach ($fieldRules as $rule) {
                [$ruleName, $parameters] = $this->parseRule($rule);
                $ruleInstance = RuleFactory::make($ruleName);

                if (!$ruleInstance->validate($field, $value, $parameters)) {
                    $this->errors[$field]['error_message'] = $ruleInstance->getMessage($field, $parameters);
                }
            }
        }
    }

    /**
     * Parse a validation rule string.
     *
     * This method splits a validation rule string into the rule name and any associated parameters.
     *
     * @param string $rule The validation rule to be parsed (e.g., "max:255").
     *
     * @return array An array where the first element is the rule name, and the second element is an array of parameters.
     */
    private function parseRule(string $rule): array
    {
        $parts = explode(':', $rule);
        $ruleName = $parts[0];
        $parameters = isset($parts[1]) ? explode(',', $parts[1]) : [];

        return [$ruleName, $parameters];
    }

    /**
     * Check if there are any validation errors.
     *
     * This method checks if there are any validation errors. It returns `true` if there are errors and `false` otherwise.
     *
     * @return bool True if there are errors, false otherwise.
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Get the validation errors.
     *
     * This method returns an array of validation errors that occurred during the validation process. Each error is associated
     * with the field name and contains an error message.
     *
     * @return array An associative array of errors where keys are field names and values are error messages.
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
