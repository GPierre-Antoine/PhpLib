<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 01/08/18
 * Time: 10:25
 */

namespace PAG\Connection;

interface Ssh2 extends Connection
{
    const UNKNOWN_FINGERPRINT = 10;

    public function connect($host, $port, AuthenticationModule $authentication_module): void;

    public function hasFingerprint():bool;

    public function getFingerprint():string;
}