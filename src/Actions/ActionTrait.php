<?php

namespace App\Actions;

use Cake\Utility\Hash;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;

/**
 * Trait ActionGettersSettersTrait
 * @package App\Actions
 */
trait ActionTrait
{

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface $response
     */
    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    /**
     * @return string|null
     */
    public function getCurrentUserId(): ?string
    {
        return $this->getRequest()->getAttribute('user_id');
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function getPostValue(string $key, $default = null)
    {
        return Hash::get($this->getPostValues(), $key, $default);
    }

    /**
     * @return array
     */
    public function getPostValues(): array
    {
        return $this->getRequest()->getParsedBody() ?? [];
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function getQueryValue(string $key, $default = null)
    {
        return Hash::get($this->getQueryValues(), $key, $default);
    }

    /**
     * @return array
     */
    public function getQueryValues(): array
    {
        return $this->getRequest()->getQueryParams() ?? [];
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @param array $args
     */
    public function setArgs(array $args): void
    {
        $this->args = $args;
    }
}