<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'tbl_usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'genero',
        'correo',
        'telefono',
        'contrasena',
        'rol_id',
        'fecha_registro'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_registro';
    protected $updatedField = '';

    protected $validationRules = [
        'nombre' => 'required|min_length[3]',
        'apellido' => 'required|min_length[3]',
        'correo' => 'required|valid_email|is_unique[tbl_usuarios.correo]',
        'contrasena' => 'required|min_length[6]',
        'rol_id' => 'required|numeric'
    ];

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['contrasena'])) {
            return $data;
        }

        $data['data']['contrasena'] = password_hash($data['data']['contrasena'], PASSWORD_DEFAULT);
        return $data;
    }

    public function findUserByEmail($email)
    {
        return $this->where('correo', $email)->first();
    }
} 