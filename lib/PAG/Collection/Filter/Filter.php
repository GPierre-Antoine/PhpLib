<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 22/10/18
 * Time: 10:14
 */

namespace PAG\Collection\Filter;


interface Filter
{
    public function filter($item): bool;
}