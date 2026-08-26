# Entornos de Desarrollo — Insert MySQL

Modernización de la práctica de 2018 que validaba un formulario y después insertaba el usuario y sus temas de interés en MySQL.

## Cambios principales

- HTML5 semántico y responsive en lugar de tablas de maquetación y elementos presentacionales.
- PHP 8.x estricto y validación independiente.
- PDO con `utf8mb4` y consultas preparadas: no se interpolan datos de usuario en SQL.
- `password_hash()` sustituye el MD5 de la entrega de 2018.
- Inserción de usuario + temas dentro de una transacción atómica.
- Configuración de conexión mediante variables de entorno.
- Esquema actual con clave foránea y borrado en cascada.
- Entorno reproducible con Docker Compose y MariaDB.

La entrega original se conserva en `legacy/` para poder comparar ambas versiones.

## Ejecutar con Docker

```bash
docker compose up --build
```

Abra `http://localhost:8080`.

## Validación sin base de datos

```bash
find src public tests -name '*.php' -print0 | xargs -0 -n1 php -l
php tests/run.php
```
