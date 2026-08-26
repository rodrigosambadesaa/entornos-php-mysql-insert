# Enunciado original — formulario PHP con inserción MySQL

Práctica de **Entornos de Desarrollo** (2018) centrada en validar un formulario de registro y persistir sus datos en MySQL.

## Objetivo

1. Presentar un formulario de alta de usuario.
2. Validar los datos introducidos antes de almacenarlos.
3. Registrar los datos personales del usuario en la base de datos.
4. Registrar también los temas de interés seleccionados por el usuario.
5. Informar de los errores de validación o de inserción y conservar un flujo de registro coherente.

La entrega histórica realizaba estas operaciones con PHP/MySQL y almacenaba la contraseña mediante MD5. La versión actual conserva la finalidad de la práctica, pero utiliza validación explícita, PDO, consultas preparadas, `password_hash()` y una transacción para usuario + intereses.

## Base de datos

El esquema necesario ya está versionado en [`database/schema.sql`](database/schema.sql) y se inicia automáticamente con el entorno MariaDB de `compose.yaml`.
