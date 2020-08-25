<?php

namespace App\Handlers;

use App\Errors\HttpValidationException;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler;
use Throwable;

/**
 * Class HttpErrorHandler
 * @package App\Handlers
 */
class HttpErrorHandler extends ErrorHandler
{
    public const BAD_REQUEST = 'BAD_REQUEST';
    public const INSUFFICIENT_PRIVILEGES = 'INSUFFICIENT_PRIVILEGES';
    public const NOT_ALLOWED = 'NOT_ALLOWED';
    public const NOT_IMPLEMENTED = 'NOT_IMPLEMENTED';
    public const RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';
    public const SERVER_ERROR = 'SERVER_ERROR';
    public const UNAUTHENTICATED = 'UNAUTHENTICATED';
    public const VALIDATION_ERROR = 'VALIDATION_ERROR';

    /**
     * @return ResponseInterface
     */
    protected function respond(): ResponseInterface
    {
        $exception = $this->exception;
        $status_code = 500;
        $type = self::SERVER_ERROR;
        $description = 'An internal error has occurred while processing your request.';
        $class = null;
        $messages = null;
        $trace = null;

        if ($exception instanceof HttpException) {
            $status_code = $exception->getCode();
            $description = $exception->getMessage();

            if ($exception instanceof HttpNotFoundException) {
                $type = self::RESOURCE_NOT_FOUND;
            } elseif ($exception instanceof HttpMethodNotAllowedException) {
                $type = self::NOT_ALLOWED;
            } elseif ($exception instanceof HttpUnauthorizedException) {
                $type = self::UNAUTHENTICATED;
            } elseif ($exception instanceof HttpForbiddenException) {
                $type = self::UNAUTHENTICATED;
            } elseif ($exception instanceof HttpBadRequestException) {
                $type = self::BAD_REQUEST;
            } elseif ($exception instanceof HttpNotImplementedException) {
                $type = self::NOT_IMPLEMENTED;
            } elseif ($exception instanceof HttpValidationException) {
                $type = self::VALIDATION_ERROR;
            }
        } else {
            $type = class_basename($exception);
        }

        if (
            ($exception instanceof Exception || $exception instanceof Throwable)
            && $this->displayErrorDetails
        ) {
            $description = $exception->getMessage();
        }

        if (
            ($exception instanceof Exception || $exception instanceof Throwable)
            && $this->displayErrorDetails
        ) {
            $class = get_class($exception);
            $trace = $exception->getTrace();
        }

        if (method_exists($exception, 'getMessages')) {
            $messages = $exception->getMessages();
        }

        $error = [
            'statusCode' => $status_code,
            'error' => [
                'type' => $type
            ]
        ];

        foreach (['description', 'class', 'messages', 'trace'] as $key) {
            if (!is_null($$key)) {
                $error['error'][$key] = $$key;
            }
        }


        $payload = json_encode($error, JSON_PRETTY_PRINT);

        $response = $this->responseFactory->createResponse($status_code);
        $response->getBody()->write($payload);

        return $response;
    }
}