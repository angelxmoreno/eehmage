<?php

namespace App\Errors;

/**
 * Class ErrorObj
 * @package App\Errors
 */
class ErrorObj implements \JsonSerializable
{
    /**
     * @var int
     */
    protected $statusCode = 500;

    /**
     * @var \Throwable
     */
    protected $exception;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var array
     */
    protected $trace;

    /**
     * @var bool
     */
    protected $showDetails = false;

    /**
     * ErrorObj constructor.
     * @param \Throwable $exception
     * @param bool $showDetails
     * @param int $statusCode
     * @param string|null $description
     */
    public function __construct(\Throwable $exception, $showDetails = false, int $statusCode = 500, string $description = null)
    {
        $this->showDetails = $showDetails;
        $this->statusCode = $statusCode;
        $this->exception = $exception;
        $this->description = $description;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize()
    {
        $arr = [
            'statusCode' => $this->getStatusCode(),
            'error' => [
                'type' => get_class($this->getException()),
                'description' => $this->getDescription(),
            ],
        ];
        if ($this->showDetails && method_exists($this->getException(), 'getTrace')) {
            $arr['error']['trace'] = $this->getException()->getTrace();
        }

        return $arr;
    }


    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode
            ? $this->statusCode
            : method_exists($this->getException(), 'getCode')
                ? (int)$this->getException()->getCode()
                : 500;
    }

    /**
     * @return \Throwable
     */
    public function getException(): \Throwable
    {
        return $this->exception;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description
            ? $this->description
            : method_exists($this->getException(), 'getMessage')
                ? $this->getException()->getMessage()
                : 'Unknown Error';
    }

    /**
     * @return array
     */
    public function getTrace(): array
    {
        return $this->trace;
    }
}