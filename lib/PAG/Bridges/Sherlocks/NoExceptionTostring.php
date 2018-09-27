<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 27/09/18
 * Time: 10:20
 */

namespace PAG\Bridges\Sherlocks;


trait NoExceptionTostring
{
    public function __toString(): string
    {
        try {
            return $this->computeString();
        }
        catch (\Exception $e) {
            return "";
        }
    }

    public abstract function computeString();

}