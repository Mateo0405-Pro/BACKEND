<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class JWT extends BaseConfig
{
    public string $key = 'your_secret_key_here';  // Cambia esto por una clave secreta larga y segura
    public int $timeToLive = 3600;  // Tiempo de vida del token en segundos (1 hora)
} 