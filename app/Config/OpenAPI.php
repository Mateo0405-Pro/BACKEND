<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class OpenAPI extends BaseConfig
{
    /**
     * Info Object
     */
    public $info = [
        'title' => 'API de Autenticación',
        'description' => 'API RESTful para sistema de autenticación con JWT',
        'version' => '1.0.0',
        'contact' => [
            'name' => 'Tu Nombre',
            'email' => 'tu@email.com'
        ],
    ];

    /**
     * Security Scheme Object
     */
    public $security = [
        'bearerAuth' => [
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT'
        ]
    ];

    /**
     * Servers Object
     */
    public $servers = [
        [
            'url' => 'http://localhost:8081/api',
            'description' => 'Servidor de Desarrollo'
        ]
    ];
} 