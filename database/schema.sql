CREATE DATABASE IF NOT EXISTS practica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE practica;

CREATE TABLE IF NOT EXISTS usuario (
    correo VARCHAR(320) NOT NULL,
    clave VARCHAR(255) NOT NULL,
    nombre VARCHAR(80) NOT NULL,
    calle VARCHAR(40) NOT NULL,
    numero INT UNSIGNED NOT NULL,
    piso VARCHAR(20) NULL,
    poblacion VARCHAR(40) NOT NULL,
    provincia TINYINT UNSIGNED NOT NULL,
    codigo_postal CHAR(5) NOT NULL,
    estado_civil CHAR(1) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    web VARCHAR(2048) NULL,
    PRIMARY KEY (correo),
    CONSTRAINT chk_provincia CHECK (provincia BETWEEN 1 AND 4),
    CONSTRAINT chk_estado_civil CHECK (estado_civil IN ('c','s','v'))
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS temas (
    correo VARCHAR(320) NOT NULL,
    tema VARCHAR(40) NOT NULL,
    PRIMARY KEY (correo, tema),
    CONSTRAINT fk_temas_usuario FOREIGN KEY (correo) REFERENCES usuario(correo) ON DELETE CASCADE
) ENGINE=InnoDB;
