<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 24/07/2018
 * Time: 23:38
 */

namespace PAG\Database;


use PDO;

class CommonDatabaseWrapper implements DatabaseWrapper
{

    /**
     * @var PDO
     */
    protected $pdo;
    protected $reconnection_function;
    private   $request_count;
    private   $db_name;
    private   $db_type;

    public function __construct(
        string $db_type,
        string $db_name,
        string $db_host,
        string $db_user,
        string $db_pass,
        string $charset = 'utf8mb4',
        array $db_options = []
    ) {
        $this->db_type = $db_type;
        $this->db_name = $db_name;

        $default_options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_CASE               => PDO::CASE_LOWER,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_PERSISTENT         => true,
        ];
        $options         = array_replace($default_options, $db_options);

        $this->reconnection_function =
            function () use ($db_type, $db_name, $db_host, $db_user, $db_pass, $charset, $options) {
                $this->makePdo($db_type, $db_name, $db_host, $db_user, $db_pass, $charset, $options);
            };
    }

    protected function makePdo(
        string $db_type,
        string $db_name,
        string $db_host,
        string $db_user,
        string $db_pass,
        string $charset,
        array $options
    ): void {
        $this->pdo = new PDO("$db_type:host=$db_host;dbname=$db_name;charset=$charset", $db_user, $db_pass, $options);
    }

    public
    static final function makeQueryFromArray(
        $array
    ): string {
        return rtrim(str_repeat('?,', count($array)), ',');
    }

    public function getDbname(): string
    {
        return $this->db_name;
    }

    public function beginTransaction(): void
    {
        $this->reconnect();
        $this->pdo->beginTransaction();
    }

    public final function reconnect(): void
    {
        if (is_null($this->pdo)) {
            call_user_func($this->reconnection_function);
        }
    }

    public function endTransaction(): void
    {
        $this->pdo->commit();
    }

    public function rollback(): void
    {
        $this->pdo->rollBack();
    }

    public function run(string $sql, ... $args): \PDOStatement
    {
        $this->reconnect();
        if (!$args) {
            $this->request_count += 1;
            $stmt                = $this->pdo->query($sql);
            $this->treatStatement($stmt);
            /** @noinspection PhpIncompatibleReturnTypeInspection */
            return $stmt;
        }
        $stmt = $this->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

    private final function treatStatement(\PDOStatement $stmt): void
    {
        $this->request_count += 1;
        $this->treatStatementChild($stmt);
    }

    protected function treatStatementChild(\PDOStatement $statement): void
    {

    }

    public function prepare(string $request): \PDOStatement
    {
        $this->reconnect();
        /** @var \PDOStatement $stmt */
        $stmt = $this->pdo->prepare($request);
        $this->treatStatement($stmt);
        return $stmt;
    }

    public function getRequestCount(): int
    {
        return $this->request_count;
    }

    public function lastInsertID(): int
    {
        return $this->pdo->lastInsertId();
    }
}