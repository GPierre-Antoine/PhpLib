<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 23/07/2018
 * Time: 19:41
 */

namespace PAG\IO;


interface Identifiable
{
    public function getId() : string;
}