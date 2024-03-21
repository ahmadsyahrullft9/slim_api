<?php

namespace Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Slim\Exception\HttpNotFoundException;

use Valitron\Validator;
use App\Auth;

use Repositories\UserKeyRepository;

class UserKeyController
{

    private UserKeyRepository $userKeyRepository;
    private Validator $validator;
    private Auth $auth;

    public function __construct(Auth $auth, UserKeyRepository $userKeyRepository, Validator $validator)
    {
        $this->auth = $auth;
        $this->userKeyRepository = $userKeyRepository;
        $this->validator = $validator;

        //set rule of form validation
        $this->validator->mapFieldsRules([
            'user_email' => ['required'],
            'user_password' => ['required']
        ]);
    }

    public function register_user(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        //validasi post request body
        $this->validator = $this->validator->withData($data);
        if (!$this->validator->validate()) {
            $response->getBody()->write(json_encode($this->validator->errors()));
            return $response->withStatus(422);
        }

        $userEmail = $data['user_email'];
        $userPassword = $data['user_password'];

        $find = $this->userKeyRepository->getByEmailPassword($userEmail, $userPassword);
        if (!$find) {
            $this->userKeyRepository->create($userEmail, $userPassword);
            $find = $this->userKeyRepository->getByEmailPassword($userEmail, $userPassword);
        }

        $userId = $find['user_id'];
        $token = $this->auth->generateJwtToken($userId);

        $body = json_encode([
            'message' => 'token created',
            'token' => $token,
            'data' => $find
        ]);
        $response->getBody()->write($body);
        return $response;
    }

    public function find_user(Request $request, Response $response): Response
    {
        $userEmail = $request->getQueryParams()['user_email'];
        $userPassword = $request->getQueryParams()['user_password'];

        $find = $this->userKeyRepository->getByEmailPassword($userEmail, $userPassword);
        if (!$find) {
            throw new HttpNotFoundException($request, 'user id not found');
        }
        $userId = $find['user_id'];
        $token = $this->auth->generateJwtToken($userId);

        $body = json_encode([
            'message' => 'token find',
            'token' => $token,
            'data' => $find
        ]);
        $response->getBody()->write($body);
        return $response;
    }
}
