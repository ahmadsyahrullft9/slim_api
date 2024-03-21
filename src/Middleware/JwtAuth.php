<?php

namespace Middleware;

use Psr\http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Exception;

use Repositories\UserKeyRepository;

use App\Auth;

class JwtAuth
{

    private Auth $auth;
    private UserKeyRepository $userKeyRepository;


    public function __construct(Auth $auth, UserKeyRepository $userKeyRepository)
    {
        $this->auth = $auth;
        $this->userKeyRepository = $userKeyRepository;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {

        $auth_header = $request->getHeader('Authorization');
        if (empty($auth_header)) {
            throw new Exception("request authorization is required");
        }
        $token = str_replace('Bearer ', '', $auth_header[0]);
        try {
            $decodedPayload = $this->auth->validateJwtToken($token);
            $userId = $decodedPayload->sub;
            $find = $this->userKeyRepository->getById($userId);
            if (!$find) {
                throw new Exception('invalid token userid');
            }
            $request = $request->withAttribute('userRequest', $find);
            return $handler->handle($request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
