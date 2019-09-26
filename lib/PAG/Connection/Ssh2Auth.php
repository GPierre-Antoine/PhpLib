<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 01/08/18
 * Time: 14:32
 */

namespace PAG\Connection;

use \RuntimeException;
use PAG\Connection\Exception\BadFingerPrint;
use PAG\Connection\Exception\FailedToConnect;

trait Ssh2Auth
{
    public function visitSsh2(Ssh2 $ssh2, $host, $port)
    {
        $this->assertCanUseSSH2();
        $connection = $this->ssh2Connect($host, $port);
        $this->ssh2Identify($connection);
        $this->checkFingerPrint($ssh2, $connection);

        return $connection;
    }

    protected function assertCanUseSSH2()
    {
        if (!function_exists('ssh2_connect')) {
            throw new RuntimeException("La fonction ssh2_connect est indisponible");
        }
    }

    /**
     * @param $host
     * @param $port
     *
     * @return resource
     */
    protected function ssh2Connect($host, $port)
    {
        $connection = ssh2_connect($host, $port);
        if (!$connection) {
            throw new FailedToConnect("Could not connect to server");
        }

        return $connection;
    }

    /**
     * @param Ssh2              $ssh2
     * @param                   $connection
     */
    private function checkFingerPrint(Ssh2 $ssh2, $connection)
    {
        if ($ssh2->hasFingerprint()) {
            $fingerprint = ssh2_fingerprint($connection);
            if ($ssh2->getFingerprint() !== $fingerprint) {
                throw new BadFingerPrint("UNKNOWN HOST FINGERPRINT = $fingerprint",
                    Ssh2::UNKNOWN_FINGERPRINT);
            }
        }
    }


}