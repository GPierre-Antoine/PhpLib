<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 01/08/2018
 * Time: 23:35
 */

namespace PAG\Connection\Exception;


use RuntimeException;

class FailedToIdentify extends RuntimeException implements PubkeyException, PasswordException
{

}