<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 01/08/2018
 * Time: 21:01
 */

namespace PAG\Connection\Exception;


use RuntimeException;

class FailedToRename extends RuntimeException implements SftpException
{

}