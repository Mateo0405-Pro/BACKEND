<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\PasswordValidator;

class UsuarioModel extends Model
{
    protected $table = 'tbl_usuarios';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
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

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[50]',
        'apellido' => 'required|min_length[3]',
        'correo' => 'required|valid_email|is_unique[tbl_usuarios.correo]',
        'contrasena' => 'required|min_length[8]',
        'rol_id' => 'required|numeric'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es requerido',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede tener más de 50 caracteres'
        ],
        'correo' => [
            'required' => 'El email es requerido',
            'valid_email' => 'Debe proporcionar un email válido',
            'is_unique' => 'Este email ya está registrado'
        ],
        'contrasena' => [
            'required' => 'La contraseña es requerida',
            'min_length' => 'La contraseña debe tener al menos 8 caracteres'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    protected $beforeInsert = ['validateAndHashPassword'];
    protected $beforeUpdate = ['validateAndHashPassword'];

    /**
     * Valida y hashea la contraseña antes de guardar
     *
     * @param array $data
     * @return array
     */
    public function beforeInsert(array $data): array
    {
        return $this->validateAndHashPassword($data);
    }

    /**
     * Valida y hashea la contraseña antes de actualizar
     *
     * @param array $data
     * @return array
     */
    public function beforeUpdate(array $data): array
    {
        return $this->validateAndHashPassword($data);
    }

    /**
     * Valida la fortaleza de la contraseña y la hashea
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    protected function validateAndHashPassword(array $data): array
    {
        if (isset($data['data']['contrasena'])) {
            $validator = new PasswordValidator();
            $validation = $validator->validate($data['data']['contrasena']);
            
            if (!$validation['isValid']) {
                throw new \Exception(implode('. ', $validation['errors']));
            }

            // Calcular y registrar la fortaleza de la contraseña
            $strength = $validator->calculateStrength($data['data']['contrasena']);
            $data['data']['contrasena_strength'] = $strength['strength'];

            // Hashear la contraseña
            $data['data']['contrasena'] = password_hash(
                $data['data']['contrasena'],
                PASSWORD_BCRYPT
            );
        }

        return $data;
    }

    /**
     * Verifica si las credenciales son válidas
     *
     * @param string $email
     * @param string $password
     * @return array|null
     */
    public function verificarCredenciales(string $email, string $password): ?array
    {
        $usuario = $this->where('correo', $email)->first();

        if ($usuario && password_verify($password, $usuario['contrasena'])) {
            unset($usuario['contrasena']); // No devolver la contraseña hasheada
            return $usuario;
        }

        return null;
    }

    public function findUserByEmail($email)
    {
        return $this->where('correo', $email)->first();
    }
} 