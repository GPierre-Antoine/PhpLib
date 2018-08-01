<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 01/08/2018
 * Time: 20:41
 */

namespace PAG\Connection;


interface Connection
{
    /**
     * @param                      $hostname
     * @param                      $port
     * @param AuthenticationModule $authenticationModule
     */
    public function connect( $hostname, $port, AuthenticationModule $authenticationModule) : void;
}