<?php
declare(strict_types=1);

final class RegistrationValidator
{
    private const PROVINCES = ['1', '2', '3', '4'];
    private const CIVIL_STATES = ['c', 's', 'v'];
    private const TOPICS = ['Música', 'Deportes', 'Lectura', 'Cine', 'Teatro', 'Coches', 'Estética', 'Móviles', 'Viajes'];

    /** @return array<string, string> */
    public static function validate(array $input): array
    {
        $errors = [];
        $email = trim((string)($input['correo'] ?? ''));
        $password = (string)($input['clave'] ?? '');
        $repeat = (string)($input['clave_repe'] ?? '');
        $name = trim((string)($input['nombre'] ?? ''));
        $street = trim((string)($input['calle'] ?? ''));
        $number = trim((string)($input['numero'] ?? ''));
        $floor = trim((string)($input['piso'] ?? ''));
        $city = trim((string)($input['poblacion'] ?? ''));
        $province = (string)($input['provincia'] ?? '');
        $postalCode = trim((string)($input['codigo_postal'] ?? ''));
        $civilState = (string)($input['estado_civil'] ?? '');
        $birthDate = trim((string)($input['fecha_nacimiento'] ?? ''));
        $web = trim((string)($input['web'] ?? ''));
        $topics = $input['temas'] ?? [];

        if (strlen($email) < 5 || strlen($email) > 320 || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['correo'] = 'Introduce un correo electrónico válido de entre 5 y 320 caracteres.';
        }
        if (strlen($password) < 4 || strlen($password) > 20) {
            $errors['clave'] = 'La contraseña debe tener entre 4 y 20 caracteres.';
        } elseif (!hash_equals($password, $repeat)) {
            $errors['clave_repe'] = 'Las contraseñas deben coincidir.';
        }
        if (strlen($name) < 3 || strlen($name) > 80) {
            $errors['nombre'] = 'El nombre debe tener entre 3 y 80 caracteres.';
        }
        if (strlen($street) < 5 || strlen($street) > 40) {
            $errors['calle'] = 'La calle debe tener entre 5 y 40 caracteres.';
        }
        if (!ctype_digit($number) || (int)$number <= 0) {
            $errors['numero'] = 'El número debe ser un entero positivo.';
        }
        if ($floor !== '' && (strlen($floor) < 3 || strlen($floor) > 20)) {
            $errors['piso'] = 'Si se indica, el piso debe tener entre 3 y 20 caracteres.';
        }
        if (strlen($city) < 3 || strlen($city) > 40) {
            $errors['poblacion'] = 'La población debe tener entre 3 y 40 caracteres.';
        }
        if (!in_array($province, self::PROVINCES, true)) {
            $errors['provincia'] = 'Selecciona una provincia.';
        }
        if (!preg_match('/^\d{5}$/D', $postalCode)) {
            $errors['codigo_postal'] = 'El código postal debe contener exactamente 5 dígitos.';
        }
        if (!in_array($civilState, self::CIVIL_STATES, true)) {
            $errors['estado_civil'] = 'Selecciona un estado civil.';
        }
        if (!self::isPastDate($birthDate)) {
            $errors['fecha_nacimiento'] = 'Introduce una fecha de nacimiento válida y anterior a hoy.';
        }
        if ($web !== '' && filter_var($web, FILTER_VALIDATE_URL) === false) {
            $errors['web'] = 'La dirección web no es válida.';
        }
        if (!is_array($topics) || count(array_intersect($topics, self::TOPICS)) === 0) {
            $errors['temas'] = 'Selecciona al menos un tema de interés.';
        }

        return $errors;
    }

    private static function isPastDate(string $value): bool
    {
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
        $parseErrors = DateTimeImmutable::getLastErrors();
        if ($date === false || ($parseErrors !== false && ($parseErrors['warning_count'] > 0 || $parseErrors['error_count'] > 0))) {
            return false;
        }
        return $date < new DateTimeImmutable('today');
    }
}
