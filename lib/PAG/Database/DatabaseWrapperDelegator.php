<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 25/07/18
 * Time: 14:06
 */

namespace PAG\Database;


class DatabaseWrapperDelegator implements DatabaseWrapper
{

    /**
     * @var DatabaseWrapper
     */
    protected $wrapper;

    public function __construct(DatabaseWrapper $wrapper)
    {
        $this->wrapper = $wrapper;
    }

    public function getDbname(): string
    {
        return $this->wrapper->getDbname();
    }

    public function beginTransaction(): void
    {
        $this->wrapper->beginTransaction();
    }

    public function reconnect(): void
    {
        $this->wrapper->reconnect();
    }

    public function endTransaction(): void
    {
        $this->wrapper->endTransaction();
    }

    public function rollback(): void
    {
        $this->wrapper->rollback();
    }

    public function run(string $sql, ...$args): \PDOStatement
    {
        return $this->wrapper->run($sql, ...$args);
    }

    public function prepare(string $request): \PDOStatement
    {
        return $this->wrapper->prepare($request);
    }

    public function getRequestCount(): int
    {
        return $this->wrapper->getRequestCount();
    }

    public function lastInsertID(): int
    {
        return $this->wrapper->lastInsertID();
    }
}