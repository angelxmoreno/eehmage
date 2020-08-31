<?php
declare(strict_types=1);

namespace App\Database\Behaviors;

use App\Errors\ValidationError;
use App\Validators\ValidationRules;
use Valitron\Validator;

/**
 * Trait UsesValidator
 * @package App\Database\Behaviors
 */
trait UsesValidator
{
    /**
     * @var array
     */
    protected $validationErrors;

    /**
     * @var bool
     */
    protected $validationIsValid;

    /**
     * @return array
     */
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return is_null($this->validationIsValid)
            ? $this->runValidation()
            : $this->validationIsValid;
    }

    /**
     * @return bool
     */
    protected function runValidation(): bool
    {
        $validator = $this->getValidator();
        $this->validationIsValid = $validator->validate();
        $this->validationErrors = $validator->errors();

        return $this->validationIsValid;
    }

    /**
     * @return Validator
     */
    protected function getValidator()
    {
        return $this->getRules(ValidationRules::init())->withData($this->toArray());
    }

    /**
     * @param Validator $validator
     * @return Validator
     */
    protected function getRules(Validator $validator)
    {
        return $validator;
    }

    /**
     * @return bool
     * @throws ValidationError
     */
    public function validateOrFail()
    {
        $validator = $this->getValidator();
        $this->validationIsValid = $validator->validate();
        $this->validationErrors = $validator->errors();

        if (!$this->validationIsValid) {
            throw new ValidationError($this->validationErrors);
        }
        return $this->validationIsValid;
    }
}
