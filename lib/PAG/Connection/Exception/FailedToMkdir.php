<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 01/08/2018
 * Time: 20:53
 */

namespace PAG\Connection\Exception;

class FailedToMkdir extends \RuntimeException implements FtpException, SftpException
{

}