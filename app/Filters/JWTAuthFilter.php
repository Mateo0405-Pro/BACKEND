<?php

namespace App\Filters;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTAuthFilter implements FilterInterface
{
    use ResponseTrait;

    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeader("Authorization");
        
        if (!$header) {
            return Services::response()
                ->setJSON([
                    'status' => 401,
                    'error' => 'Token no proporcionado'
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        try {
            $token = explode(' ', $header->getValue())[1];
            JWT::decode($token, new Key(getenv('JWT_SECRET'), 'HS256'));
            return $request;
        } catch (Exception $e) {
            return Services::response()
                ->setJSON([
                    'status' => 401,
                    'error' => 'Token inválido'
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
} 