<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 25/07/18
 * Time: 17:05
 */

namespace PAG\Traits;


trait LazyGetters
{
    public function __get($name)
    {
        return $this->$name;
    }
}