# Sistema de Autenticación con CodeIgniter 4 y SQL Server

Este proyecto implementa un sistema de autenticación usando CodeIgniter 4 como framework backend y SQL Server como base de datos.

## Características

- Registro de usuarios
- Inicio de sesión con JWT
- Documentación con Swagger/OpenAPI
- Validaciones de datos
- Protección de rutas con JWT

## Requisitos

- PHP 8.0 o superior
- SQL Server
- Extensión sqlsrv de PHP
- Composer

## Instalación

1. Clonar el repositorio:
```bash
git clone <url-del-repositorio>
```

2. Instalar dependencias:
```bash
composer install
```

3. Configurar la base de datos:
   - Crear una base de datos en SQL Server
   - Configurar las credenciales en `app/Config/Database.php`

4. Ejecutar las migraciones:
```bash
php spark migrate
```

5. Iniciar el servidor:
```bash
php spark serve
```

## Documentación API

La documentación de la API está disponible en:
- Swagger UI: `http://localhost:8081/docs`
- JSON: `http://localhost:8081/swagger.json`

## Estructura del Proyecto

- `app/Controllers/Auth.php`: Controlador de autenticación
- `app/Models/UsuarioModel.php`: Modelo de usuarios
- `app/Filters/JWTAuthFilter.php`: Filtro de autenticación JWT
- `app/Config/`: Archivos de configuración

## Contribuir

1. Fork el proyecto
2. Crear una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir un Pull Request
