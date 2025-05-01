<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use OpenApi\Generator;

class Swagger extends Controller
{
    public function index()
    {
        return view('swagger');
    }

    public function json()
    {
        $openapi = Generator::scan([APPPATH . 'Controllers']);
        return $this->response->setJSON($openapi);
    }
} 