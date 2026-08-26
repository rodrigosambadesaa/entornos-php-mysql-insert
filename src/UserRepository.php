<?php
declare(strict_types=1);

final class UserRepository
{
    public function __construct(private PDO $pdo) {}

    public function create(array $data): void
    {
        $topics = array_values((array)($data['temas'] ?? []));
        $this->pdo->beginTransaction();
        try {
            $user = $this->pdo->prepare(
                'INSERT INTO usuario (correo, clave, nombre, calle, numero, piso, poblacion, provincia, codigo_postal, estado_civil, fecha_nacimiento, web)
                 VALUES (:correo, :clave, :nombre, :calle, :numero, :piso, :poblacion, :provincia, :codigo_postal, :estado_civil, :fecha_nacimiento, :web)'
            );
            $user->execute([
                'correo' => trim((string)$data['correo']),
                'clave' => password_hash((string)$data['clave'], PASSWORD_DEFAULT),
                'nombre' => trim((string)$data['nombre']),
                'calle' => trim((string)$data['calle']),
                'numero' => (int)$data['numero'],
                'piso' => trim((string)($data['piso'] ?? '')) ?: null,
                'poblacion' => trim((string)$data['poblacion']),
                'provincia' => (int)$data['provincia'],
                'codigo_postal' => trim((string)$data['codigo_postal']),
                'estado_civil' => (string)$data['estado_civil'],
                'fecha_nacimiento' => (string)$data['fecha_nacimiento'],
                'web' => trim((string)($data['web'] ?? '')) ?: null,
            ]);

            $topicStatement = $this->pdo->prepare('INSERT INTO temas (correo, tema) VALUES (:correo, :tema)');
            foreach ($topics as $topic) {
                $topicStatement->execute(['correo' => trim((string)$data['correo']), 'tema' => (string)$topic]);
            }
            $this->pdo->commit();
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }
}
