<?php
declare(strict_types=1);

namespace App\Middlewares;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

/**
 * Class ValidApiKeyMiddleware
 * @package App\Middlewares
 */
class ValidApiKeyMiddleware
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * ValidApiKeyMiddleware constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param RequestHandler $handler
     * @return \Psr\Http\Message\MessageInterface|ResponseInterface|\Slim\Psr7\Message
     */
    public function __invoke(Request $request, RequestHandler $handler)
    {
        $reqApiKey = $this->getRequestApiKey($request);
        $appApiKey = $this->container->get('app.apiKey');
        if ($reqApiKey !== $appApiKey) {
            $response = new Response();
            return $response
                ->withStatus(401)
                ->withHeader('Content-Type', 'application/json');
        }
        return $handler->handle($request);
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function getRequestApiKey(Request $request)
    {
        $header = $request->getHeaderLine('Authorization') ?? '';
        return str_ireplace('Bearer ', '', $header);
    }
}
