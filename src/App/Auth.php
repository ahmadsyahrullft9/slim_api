<?php

declare(strict_types=1);

namespace App;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Exception;

class Auth
{

    private $alg; //HS256
    private $secretKey;

    public function __construct($alg, $secretKey)
    {
        $this->alg = $alg;
        $this->secretKey = $secretKey;
    }

    public function generateJwtToken($userId)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + (60 * 60 * 24); // valid for 1 day

        $payload = array(
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'sub' => $userId
        );

        return JWT::encode($payload, $this->secretKey, $this->alg);
    }

    public function validateJwtToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, $this->alg));
            return $decoded;
        } catch (ExpiredException $e) {
            throw new ExpiredException('Token expired');
        } catch (SignatureInvalidException $e) {
            throw new ExpiredException('Invalid token signature');
        } catch (BeforeValidException $e) {
            throw new ExpiredException('Token not valid yet');
        } catch (Exception $e) {
            throw new Exception('Invalid token');
        }
    }
}
