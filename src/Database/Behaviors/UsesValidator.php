<?php
declare(strict_types=1);

namespace App\Database\Behaviors;

use App\Database\ModelBase;
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

    public static function bootUsesValidator()
    {
        static::creating(function (ModelBase $model) {
            return $model->runValidation();
        });
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
        return $this->getRules(new Validator())->withData($this->toArray());
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
        return $this->validationIsValid;
    }
}
