<?php


namespace app\core;

use PDO;
use app\constants\Migration;

class Database
{
    public PDO $pdo;
    protected array $handledMigrationsName = [];

    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new PDO($dsn, $user, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations(string $action, ?string $migrationNumber)
    {
        $this->createMigrationsTable();
        switch ($action) {
            case Migration::LONG_OPTION_UP:
                if (is_null($migrationNumber)) {
                    $appliedMigrations = $this->getAppliedMigrations();
                    $files = scandir(sprintf('%s/migrations', Application::$ROOT_DIR));
                    $migrationsToApply = array_diff($files, $appliedMigrations);
                } else {
                    $migrationsToApply = $this->getMigrationByNumber($migrationNumber);
                    if (count($migrationsToApply) > 0) {
                        $this->log(sprintf('Миграция с номером %s уже применена', $migrationNumber));
                        exit;
                    }

                    $files = scandir(sprintf('%s/migrations', Application::$ROOT_DIR));
                    $migrationsToApply = array_filter($files, function ($fileName) use ($migrationNumber) {
                        return strpos($fileName, $migrationNumber) !== false;
                    });
                }
                $this->migrationsUpAction($migrationsToApply);
                break;
            case Migration::LONG_OPTION_DOWN:
                if (is_null($migrationNumber)) {
                    $appliedMigrations = $this->getAppliedMigrations(Migration::ORDER_WAY_DESC);
                } else {
                    $appliedMigrations = $this->getMigrationByNumber($migrationNumber);
                    if (count($appliedMigrations) === 0) {
                        $this->log(sprintf('Миграция с номером %s не найдена', $migrationNumber));
                        exit;
                    }
                }
                $this->migrationsDownAction($appliedMigrations);
                break;
        }
    }

    public function createMigrationsTable()
    {
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(225),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;');
    }

    public function getAppliedMigrations($orderWay = Migration::ORDER_WAY_ASC)
    {
        $statement = $this->pdo->prepare(
            sprintf('SELECT migration FROM migrations ORDER BY created_at %s', $orderWay)
        );
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    public function saveMigrations(array $migrationsName)
    {
        $valuesStr = implode(',', array_map(fn($m) => sprintf("('%s')", $m), $migrationsName));
        $statement = $this->pdo->prepare(sprintf('INSERT INTO migrations (migration) VALUES %s', $valuesStr));
        $statement->execute();
    }

    protected function log($message)
    {
        echo sprintf('[%s] - %s%s', date('Y-m-d H:i:s'), $message, PHP_EOL);
    }

    protected function migrationsUpAction(array $migrationsToUp)
    {
        foreach ($migrationsToUp as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            require_once sprintf('%s/migrations/%s', Application::$ROOT_DIR, $migration);
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $className = sprintf('\app\migrations\%s', $className);
            $instance = new $className();
            $this->log(sprintf('Applying migration %s', $migration));
            $instance->up();
            $this->log('Successfully!');
            $this->handledMigrationsName[] = $migration;
        }

        if (count($this->handledMigrationsName) > 0) {
            $this->saveMigrations($this->handledMigrationsName);
        } else {
            $this->log('There is nothing to migrate');
        }
    }

    protected function getMigrationByNumber(string $number): array
    {
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        $statement = $this->pdo->prepare(
            'SELECT migration FROM migrations WHERE migration LIKE :number LIMIT 1'
        );
        $statement->bindValue('number', sprintf('%s%%',$number));

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    protected function migrationsDownAction(array $migrationsToDown)
    {
        foreach ($migrationsToDown as $migration) {
            require_once sprintf('%s/migrations/%s', Application::$ROOT_DIR, $migration);
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $className = sprintf('\app\migrations\%s', $className);
            $instance = new $className();
            $this->log(sprintf('Downing migration %s', $migration));
            $instance->down();
            $this->log('Successfully!');
            $this->handledMigrationsName[] = $migration;
        }

        if (count($this->handledMigrationsName) > 0) {
            $this->removeMigrations($this->handledMigrationsName);
        } else {
            $this->log('There is nothing to down');
        }
    }

    public function removeMigrations(array $migrationsName)
    {
        $migrationsName = array_map(fn($mn) => sprintf("'%s'", $mn), $migrationsName);
        $statement = $this->pdo->prepare(
            sprintf('DELETE FROM migrations WHERE migration IN (%s)', implode(',', $migrationsName))
        );
        $statement->execute();
    }
}