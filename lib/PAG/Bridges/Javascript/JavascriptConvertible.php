<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Antoine
 * Date: 23/07/2018
 * Time: 19:41
 */

namespace PAG\Bridges\Javascript;


use PAG\IO\Identifiable;

interface JavascriptConvertible extends Identifiable
{
    public function getClassName()       : string;
    public function getMotherClassName() : string;
}