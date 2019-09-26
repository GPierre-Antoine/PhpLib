<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 01/08/2018
 * Time: 23:36
 */

namespace PAG\Connection\Exception;


use RuntimeException;

class BadFingerPrint extends RuntimeException implements Ssh2Exception
{

}