<?php


namespace Middleware;


use Psr\http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Slim\Routing\RouteContext;
use Slim\Exception\HttpNotFoundException;

use Repositories\UserRepository;

class GetUser
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $context = RouteContext::fromRequest($request);
        $route = $context->getRoute();

        $id = $route->getArgument('id');
        $user = $this->userRepository->getById($id);
        if ($user == false) {
            throw new HttpNotFoundException($request, 'user not found');
        }

        $request = $request->withAttribute('user', $user);
        return $handler->handle($request);
    }
}
