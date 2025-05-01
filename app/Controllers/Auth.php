<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\UsuarioModel;
use Firebase\JWT\JWT;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API de Autenticación",
 *     description="API RESTful para sistema de autenticación con JWT",
 *     @OA\Contact(
 *         name="Tu Nombre",
 *         email="tu@email.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8081/api",
 *     description="Servidor de Desarrollo"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class Auth extends BaseController
{
    use ResponseTrait;

    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"Autenticación"},
     *     summary="Iniciar sesión",
     *     description="Endpoint para autenticar usuarios y obtener token JWT",
     *     operationId="login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"correo","contrasena"},
     *             @OA\Property(property="correo", type="string", format="email", example="usuario@ejemplo.com"),
     *             @OA\Property(property="contrasena", type="string", format="password", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Juan"),
     *                 @OA\Property(property="correo", type="string", example="juan@ejemplo.com"),
     *                 @OA\Property(property="rol_id", type="integer", example=2)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales inválidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Contraseña incorrecta")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Usuario no encontrado")
     *         )
     *     )
     * )
     */
    public function login()
    {
        $rules = [
            'correo' => 'required|valid_email',
            'contrasena' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors(), 400);
        }

        $correo = $this->request->getVar('correo');
        $contrasena = $this->request->getVar('contrasena');

        $usuario = $this->usuarioModel->findUserByEmail($correo);

        if (!$usuario) {
            return $this->failNotFound('Usuario no encontrado');
        }

        if (!password_verify($contrasena, $usuario['contrasena'])) {
            return $this->fail('Contraseña incorrecta', 401);
        }

        $jwtConfig = new \Config\JWT();
        $key = $jwtConfig->key;
        $iat = time();
        $exp = $iat + $jwtConfig->timeToLive;

        $payload = [
            'iss' => 'your_app_name',
            'aud' => 'your_app_audience',
            'sub' => $usuario['id'],
            'iat' => $iat,
            'exp' => $exp
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        $response = [
            'status' => 200,
            'token' => $token,
            'user' => [
                'id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'correo' => $usuario['correo'],
                'rol_id' => $usuario['rol_id']
            ]
        ];

        return $this->respond($response);
    }

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     tags={"Autenticación"},
     *     summary="Registrar nuevo usuario",
     *     description="Endpoint para registrar nuevos usuarios en el sistema",
     *     operationId="register",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","apellido","correo","contrasena"},
     *             @OA\Property(property="nombre", type="string", example="Juan"),
     *             @OA\Property(property="apellido", type="string", example="Pérez"),
     *             @OA\Property(property="correo", type="string", format="email", example="juan@ejemplo.com"),
     *             @OA\Property(property="contrasena", type="string", format="password", example="123456"),
     *             @OA\Property(property="telefono", type="string", example="1234567890"),
     *             @OA\Property(property="fecha_nacimiento", type="string", format="date", example="1990-01-01"),
     *             @OA\Property(property="genero", type="string", enum={"M", "F", "O"}, example="M")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario registrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="Usuario registrado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al registrar usuario")
     *         )
     *     )
     * )
     */
    public function register()
    {
        try {
            $rules = [
                'nombre' => 'required|min_length[3]',
                'apellido' => 'required|min_length[3]',
                'correo' => 'required|valid_email|is_unique[tbl_usuarios.correo]',
                'contrasena' => 'required|min_length[6]'
            ];

            if (!$this->validate($rules)) {
                log_message('error', 'Validation failed: ' . json_encode($this->validator->getErrors()));
                return $this->fail($this->validator->getErrors(), 400);
            }

            $data = [
                'nombre' => $this->request->getVar('nombre'),
                'apellido' => $this->request->getVar('apellido'),
                'correo' => $this->request->getVar('correo'),
                'contrasena' => password_hash($this->request->getVar('contrasena'), PASSWORD_DEFAULT),
                'telefono' => $this->request->getVar('telefono'),
                'fecha_nacimiento' => $this->request->getVar('fecha_nacimiento'),
                'genero' => $this->request->getVar('genero'),
                'rol_id' => 2, // Role por defecto para usuarios nuevos
                'fecha_registro' => date('Y-m-d H:i:s')
            ];

            log_message('info', 'Attempting to insert user with data: ' . json_encode($data));

            $db = \Config\Database::connect();
            $builder = $db->table('tbl_usuarios');

            // Verificar si el correo ya existe
            $existingUser = $builder->where('correo', $data['correo'])->get()->getRow();
            if ($existingUser) {
                log_message('error', 'Email already exists: ' . $data['correo']);
                return $this->fail(['correo' => 'El correo ya está registrado'], 400);
            }

            $result = $builder->insert($data);

            if ($result) {
                log_message('info', 'User registered successfully with ID: ' . $db->insertID());
                return $this->respondCreated([
                    'status' => 201,
                    'message' => 'Usuario registrado exitosamente',
                    'user_id' => $db->insertID()
                ]);
            } else {
                log_message('error', 'Failed to insert user. Database error: ' . json_encode($db->error()));
                return $this->fail('Error al registrar usuario: ' . json_encode($db->error()), 500);
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception while registering user: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->fail('Error al registrar usuario: ' . $e->getMessage(), 500);
        }
    }
} 