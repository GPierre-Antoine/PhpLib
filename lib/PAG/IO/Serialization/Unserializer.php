<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 24/07/2018
 * Time: 22:57
 */

namespace PAG\IO\Serialization;


interface Unserializer
{
    public function unserialize($object);
}