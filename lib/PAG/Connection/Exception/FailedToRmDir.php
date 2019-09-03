<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 01/08/2018
 * Time: 21:09
 */

namespace PAG\Connection\Exception;


use RuntimeException;

class FailedToRmDir extends RuntimeException implements SftpException
{

}