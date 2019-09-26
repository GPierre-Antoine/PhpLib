<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 01/08/2018
 * Time: 20:52
 */

namespace PAG\Connection\Exception;

use RuntimeException;

class FailedToDelete extends RuntimeException implements FtpException, SftpException
{

}