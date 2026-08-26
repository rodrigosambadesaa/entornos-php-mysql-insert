<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/src/RegistrationValidator.php';
require_once dirname(__DIR__) . '/src/Database.php';
require_once dirname(__DIR__) . '/src/UserRepository.php';

function h(string $value): string { return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function old(string $key): string { return h((string)($_POST[$key] ?? '')); }
function checked(string $topic): string { return in_array($topic, (array)($_POST['temas'] ?? []), true) ? ' checked' : ''; }
function selected(string $key, string $value): string { return (string)($_POST[$key] ?? '') === $value ? ' selected' : ''; }

$errors = [];
$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = RegistrationValidator::validate($_POST);
    if ($errors === []) {
        try {
            (new UserRepository(Database::connect()))->create($_POST);
            $message = 'Los datos se han validado e insertado correctamente.';
            $_POST = [];
        } catch (PDOException $e) {
            $errors['database'] = $e->getCode() === '23000'
                ? 'Ya existe un usuario con ese correo electrónico.'
                : 'No se pudo completar el registro en la base de datos.';
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Práctica modernizada de inserción MySQL con PHP, PDO y HTML5.">
  <title>Registro con MySQL</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<main class="container">
<header><p class="eyebrow">Entornos de Desarrollo · PHP + MySQL</p><h1>Formulario de registro</h1><p>Versión modernizada con HTML5, PDO, consultas preparadas y transacciones.</p></header>
<?php if ($message): ?><section class="notice success" aria-live="polite"><h2>Registro completado</h2><p><?= h($message) ?></p></section><?php endif; ?>
<?php if ($errors): ?><section class="notice error" aria-live="assertive"><h2>Revisa los datos</h2><ul><?php foreach($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></section><?php endif; ?>
<form method="post" action="index.php">
<fieldset><legend>Datos de identificación</legend>
<label>Correo electrónico *<input type="email" name="correo" value="<?= old('correo') ?>" minlength="5" maxlength="320" autocomplete="email" required></label>
<label>Contraseña *<input type="password" name="clave" minlength="4" maxlength="20" autocomplete="new-password" required></label>
<label>Repita contraseña *<input type="password" name="clave_repe" minlength="4" maxlength="20" autocomplete="new-password" required></label>
<label>Nombre y apellidos *<input type="text" name="nombre" value="<?= old('nombre') ?>" minlength="3" maxlength="80" autocomplete="name" required></label>
</fieldset>
<fieldset><legend>Dirección</legend>
<label>Calle *<input type="text" name="calle" value="<?= old('calle') ?>" minlength="5" maxlength="40" required></label>
<label>Número *<input type="number" name="numero" value="<?= old('numero') ?>" min="1" step="1" required></label>
<label>Piso<input type="text" name="piso" value="<?= old('piso') ?>" maxlength="20"></label>
<label>Población *<input type="text" name="poblacion" value="<?= old('poblacion') ?>" minlength="3" maxlength="40" required></label>
<label>Provincia *<select name="provincia" required><option value="">Seleccione…</option><option value="1"<?= selected('provincia','1') ?>>A Coruña</option><option value="2"<?= selected('provincia','2') ?>>Lugo</option><option value="3"<?= selected('provincia','3') ?>>Ourense</option><option value="4"<?= selected('provincia','4') ?>>Pontevedra</option></select></label>
<label>Código postal *<input type="text" name="codigo_postal" value="<?= old('codigo_postal') ?>" pattern="[0-9]{5}" maxlength="5" inputmode="numeric" required></label>
</fieldset>
<fieldset><legend>Datos personales</legend>
<label>Estado civil *<select name="estado_civil" required><option value="">Seleccione…</option><option value="c"<?= selected('estado_civil','c') ?>>Casada/o</option><option value="s"<?= selected('estado_civil','s') ?>>Soltera/o</option><option value="v"<?= selected('estado_civil','v') ?>>Viuda/o</option></select></label>
<label>Fecha de nacimiento *<input type="date" name="fecha_nacimiento" value="<?= old('fecha_nacimiento') ?>" required></label>
<label>Página web<input type="url" name="web" value="<?= old('web') ?>" placeholder="https://ejemplo.com"></label>
</fieldset>
<fieldset><legend>Temas de interés *</legend><div class="checks"><?php foreach(['Música','Deportes','Lectura','Cine','Teatro','Coches','Estética','Móviles','Viajes'] as $topic): ?><label><input type="checkbox" name="temas[]" value="<?= h($topic) ?>"<?= checked($topic) ?>> <?= h($topic) ?></label><?php endforeach; ?></div></fieldset>
<div class="actions"><button type="submit">Registrar</button><button type="reset" class="secondary">Borrar</button></div>
</form>
</main>
</body></html>
