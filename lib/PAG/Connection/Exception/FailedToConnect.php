<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 01/08/2018
 * Time: 21:18
 */

namespace PAG\Connection\Exception;


class FailedToConnect extends \RuntimeException implements PasswordException, PubkeyException
{

}