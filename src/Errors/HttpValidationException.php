<?php
declare(strict_types=1);

namespace App\Errors;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;

/**
 * Class HttpValidationException
 * @package App\Errors
 */
class HttpValidationException extends HttpBadRequestException
{
    /**
     * @var ValidationError
     */
    protected $validationError;

    /**
     * HttpValidationException constructor.
     * @param ServerRequestInterface $request
     * @param ValidationError $validationError
     */
    public function __construct(ServerRequestInterface $request, ValidationError $validationError)
    {
        $this->validationError = $validationError;
        parent::__construct($request, $validationError->getMessage());
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->validationError->getValidationErrors();
    }

}