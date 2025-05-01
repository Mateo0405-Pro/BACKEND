-- Crear la base de datos
IF NOT EXISTS (SELECT name FROM sys.databases WHERE name = 'auth_db')
BEGIN
    CREATE DATABASE auth_db;
END
GO

USE auth_db;
GO

-- Tabla de roles
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[tbl_roles]') AND type in (N'U'))
BEGIN
    CREATE TABLE tbl_roles (
        id INT IDENTITY(1,1) PRIMARY KEY,
        nombre_rol NVARCHAR(50) NOT NULL
    );
END
GO

-- Tabla de usuarios
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[tbl_usuarios]') AND type in (N'U'))
BEGIN
    CREATE TABLE tbl_usuarios (
        id INT IDENTITY(1,1) PRIMARY KEY,
        nombre NVARCHAR(100) NOT NULL,
        apellido NVARCHAR(100) NOT NULL,
        fecha_nacimiento DATE NULL,
        genero CHAR(1) CHECK (genero IN ('M', 'F', 'O')),
        correo NVARCHAR(100) NOT NULL UNIQUE,
        telefono NVARCHAR(20) NULL,
        contrasena NVARCHAR(255) NOT NULL,
        rol_id INT NOT NULL,
        fecha_registro DATETIME DEFAULT GETDATE(),
        CONSTRAINT FK_Usuario_Rol FOREIGN KEY (rol_id) REFERENCES tbl_roles(id)
    );
END
GO

-- Insertar roles básicos
IF NOT EXISTS (SELECT * FROM tbl_roles WHERE nombre_rol = 'Administrador')
BEGIN
    INSERT INTO tbl_roles (nombre_rol) VALUES ('Administrador');
END

IF NOT EXISTS (SELECT * FROM tbl_roles WHERE nombre_rol = 'Usuario')
BEGIN
    INSERT INTO tbl_roles (nombre_rol) VALUES ('Usuario');
END
GO

-- Insertar un usuario administrador de prueba
IF NOT EXISTS (SELECT * FROM tbl_usuarios WHERE correo = 'admin@sistema.com')
BEGIN
    INSERT INTO tbl_usuarios (nombre, apellido, correo, contrasena, rol_id) 
    VALUES ('Admin', 'Sistema', 'admin@sistema.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1); -- password: password
END
GO 