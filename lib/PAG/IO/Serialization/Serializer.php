<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 24/07/2018
 * Time: 22:52
 */

namespace PAG\IO\Serialization;


interface Serializer
{
    public function serialize($data);
}