-- Tabla Persona
CREATE TABLE Persona (
    id_persona INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    estado SMALLINT DEFAULT 1, -- 1 para activo, 0 para inactivo
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

--tabla Usuario actualizada
CREATE TABLE Usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    id_persona INT NOT NULL, -- FK hacia Persona
    usuario VARCHAR(50) NOT NULL UNIQUE,
    correo VARCHAR(100) NOT NULL UNIQUE, -- Correo único para cada usuario
    contrasena VARCHAR(255) NOT NULL, -- Almacenará la contraseña en formato hash
    estado SMALLINT DEFAULT 1, -- 1 para activo, 0 para inactivo
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_persona) REFERENCES Persona(id_persona) ON DELETE CASCADE
);

-- Índice para optimizar la búsqueda por correo
CREATE INDEX idx_usuario_correo ON Usuario (correo);

-- Índice para estado en Usuario
CREATE INDEX idx_usuario_estado ON Usuario (estado);

-- Tabla Producto
CREATE TABLE Producto (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen VARCHAR(500), -- URL completa de la imagen
    estado SMALLINT DEFAULT 1, -- 1 para activo, 0 para inactivo
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Índice para estado en Producto
CREATE INDEX idx_producto_estado ON Producto (estado);

-- Tabla Post
CREATE TABLE Post (
    id_post INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_usuario INT NOT NULL,
    descripcion TEXT, -- Información adicional específica para este post
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado SMALLINT DEFAULT 1, -- 1 para activo, 0 para inactivo
    FOREIGN KEY (id_producto) REFERENCES Producto(id_producto) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario) ON DELETE CASCADE
);

-- Tabla Notificacion
CREATE TABLE Notificacion (
    id_notificacion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    mensaje TEXT NOT NULL,
    leido BOOLEAN DEFAULT FALSE,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado SMALLINT DEFAULT 1, -- 1 para activo, 0 para inactivo
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario) ON DELETE CASCADE
);

-- Tabla Mensaje
CREATE TABLE Mensaje (
    id_mensaje INT AUTO_INCREMENT PRIMARY KEY,
    remitente INT NOT NULL,
    destinatario INT NOT NULL,
    mensaje TEXT NOT NULL,
    leido BOOLEAN DEFAULT FALSE, -- Estado de lectura del mensaje
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado SMALLINT DEFAULT 1, -- 1 para activo, 0 para inactivo
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (remitente) REFERENCES Usuario(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (destinatario) REFERENCES Usuario(id_usuario) ON DELETE CASCADE
);


