<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 25/07/18
 * Time: 14:05
 */

namespace PAG\Database;

interface DatabaseWrapper
{
    public function getDbname(): string;

    public function beginTransaction(): void;

    public function reconnect(): void;

    public function endTransaction(): void;

    public function rollback(): void;

    public function run(string $sql, ...$args): \PDOStatement;

    public function prepare(string $request): \PDOStatement;

    public function getRequestCount(): int;

    public function lastInsertID(): int;
}