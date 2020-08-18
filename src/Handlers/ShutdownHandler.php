<?php

namespace App\Handlers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\ResponseEmitter;

/**
 * Class ShutdownHandler
 * @package App\Handlers
 */
class ShutdownHandler
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var HttpErrorHandler
     */
    private $errorHandler;

    /**
     * @var bool
     */
    private $displayErrorDetails;

    /**
     * ShutdownHandler constructor.
     *
     * @param Request $request
     * @param HttpErrorHandler $errorHandler
     * @param bool $displayErrorDetails
     */
    public function __construct(Request $request, HttpErrorHandler $errorHandler, bool $displayErrorDetails)
    {
        $this->request = $request;
        $this->errorHandler = $errorHandler;
        $this->displayErrorDetails = $displayErrorDetails;
    }

    public function __invoke()
    {
        $error = error_get_last();
        if ($error) {
            $error_file = $error['file'];
            $error_line = $error['line'];
            $error_message = $error['message'];
            $error_type = $error['type'];
            $message = 'An error while processing your request. Please try again later.';

            if ($this->displayErrorDetails) {
                switch ($error_type) {
                    case E_USER_ERROR:
                        $message = "FATAL ERROR: {$error_message}. ";
                        $message .= " on line {$error_line} in file {$error_file}.";
                        break;

                    case E_USER_WARNING:
                        $message = "WARNING: {$error_message}";
                        break;

                    case E_USER_NOTICE:
                        $message = "NOTICE: {$error_message}";
                        break;

                    default:
                        $message = "ERROR: {$error_message}";
                        $message .= " on line {$error_line} in file {$error_file}.";
                        break;
                }
            }

            $exception = new HttpInternalServerErrorException($this->request, $message);
            $response = $this->errorHandler->__invoke($this->request, $exception, $this->displayErrorDetails, false, false);

            if (ob_get_length()) {
                ob_clean();
            }

            $response_emitter = new ResponseEmitter();
            $response_emitter->emit($response);
        }
    }
}