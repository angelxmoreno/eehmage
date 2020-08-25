<?php
declare(strict_types=1);

namespace App\Errors;

/**
 * Class ValidationError
 * @package App\Errors
 */
class ValidationError extends \Exception
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $title = 'Validation Error';

    /**
     * @var array
     */
    protected $validationErrors;

    /**
     * ValidationError constructor.
     * @param array $validationErrors
     * @param string $name
     */
    public function __construct(array $validationErrors = [], ?string $name = null)
    {
        $this->validationErrors = $validationErrors;
        $name = $name ? $name . ' ' . $this->title : $this->title;
        parent::__construct($name, 400);
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->getValidationErrors();
    }

    /**
     * @return array
     */
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}
