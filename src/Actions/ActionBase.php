<?php
declare(strict_types=1);

namespace App\Actions;

use Cake\Utility\Hash;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;

/**
 * Class ActionBase
 * @package App\Actions
 *
 */
abstract class ActionBase
{
    use ActionTrait;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $args;

    /**
     * ActionBase constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $container->get(Capsule::class);
        $this->setContainer($container);
    }

    /**
     * @param Request $request
     * @param ResponseInterface $response
     * @param array|null $args
     * @return ResponseInterface
     */
    public function __invoke(Request $request, ResponseInterface $response, array $args = [])
    {
        $this->setRequest($request);
        $this->setResponse($response);
        $this->setArgs($args);
        $this->run();

        return $this->getResponse();
    }

    abstract function run();


    /**
     * @return string|null
     */
    protected function getCurrentUserId(): ?string
    {
        return $this->getRequest()->getAttribute('user_id');
    }


    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    protected function getPostValue(string $key, $default = null)
    {
        return Hash::get($this->getPostValues(), $key, $default);
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    protected function getQueryValue(string $key, $default = null)
    {
        return Hash::get($this->getQueryValues(), $key, $default);
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function getArg(string $key, $default = null)
    {
        return Hash::get($this->getArgs(), $key, $default);
    }

    /**
     * @param mixed $data
     * @param int $status
     */
    protected function setData($data, int $status = 200)
    {
        $response = $this->getResponse()->withStatus($status);
        $this->setResponse($response);
        if (!is_scalar($data)) {
            $data = json_encode($data);
            $response = $this->getResponse()->withHeader('Content-Type', 'application/json');
            $this->setResponse($response);
        }

        $this->getResponse()->getBody()->write($data);
    }
}