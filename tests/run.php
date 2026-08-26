<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/RegistrationValidator.php';
$valid=['correo'=>'a@example.com','clave'=>'abcd','clave_repe'=>'abcd','nombre'=>'Ana Pérez','calle'=>'Rúa Nova','numero'=>'1','piso'=>'2 A','poblacion'=>'Santiago','provincia'=>'1','codigo_postal'=>'15701','estado_civil'=>'s','fecha_nacimiento'=>'1990-01-01','web'=>'','temas'=>['Cine']];
if (RegistrationValidator::validate($valid)!==[]) {fwrite(STDERR,"valid input rejected\n");exit(1);} 
$source=file_get_contents(__DIR__.'/../src/UserRepository.php');
foreach(['prepare(', 'password_hash(', 'beginTransaction(', 'rollBack('] as $needle){if(!str_contains($source,$needle)){fwrite(STDERR,"missing $needle\n");exit(1);}}
echo "OK validator and secure repository patterns\n";
