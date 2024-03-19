<?php

namespace Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Slim\Exception\HttpNotFoundException;

use Valitron\Validator;

use Repositories\UserRepository;

class UserController
{

    private UserRepository $userRepository;
    private Validator $validator;

    public function __construct(UserRepository $userRepository, Validator $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;

        //set rule of form validation
        $this->validator->mapFieldsRules([
            'nama' => ['required'],
            'alamat' => ['required'],
            'gender' => ['required'],
            'umur' => ['required', 'integer', ['min', 1]],
            'username' => ['required'],
            'password' => ['required'],
            'jabatan' => ['required'],
            'level' => ['required']
        ]);
    }

    public function show(Request $request, Response $response): Response
    {
        $data = $this->userRepository->getAll();
        if ($data === false) {
            throw new HttpNotFoundException($request, 'user not found');
        }
        $body = json_encode($data);
        $response->getBody()->write($body);
        return $response;
    }

    public function find(Request $request, Response $response): Response
    {
        //midleware request
        $data = $request->getAttribute('user');
        $body = json_encode($data);
        $response->getBody()->write($body);
        return $response;
    }

    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        //validasi post request body
        $this->validator = $this->validator->withData($data);
        if (!$this->validator->validate()) {
            $response->getBody()->write(json_encode($this->validator->errors()));
            return $response->withStatus(422);
        }

        $created = $this->userRepository->create($data);
        $body = json_encode([
            'message' => 'user added',
            'id' => $created
        ]);
        $response->getBody()->write($body);

        return $response->withStatus(201);
    }

    public function update(Request $request, Response $response, string $id): Response
    {
        $data = $request->getParsedBody();

        //validasi post request body
        $this->validator = $this->validator->withData($data);
        if (!$this->validator->validate()) {
            $response->getBody()->write(json_encode($this->validator->errors()));
            return $response->withStatus(422);
        }

        $updated = $this->userRepository->update($id, $data);
        $body = json_encode([
            'message' => 'user updated',
            'rows' => $updated
        ]);
        $response->getBody()->write($body);

        return $response->withStatus(201);
    }

    public function delete(Request $request, Response $response, string $id): Response
    {
        $deleted = $this->userRepository->delete($id);
        $body = json_encode([
            'message' => 'user deleted',
            'rows' => $deleted
        ]);
        $response->getBody()->write($body);

        return $response;
    }
}
