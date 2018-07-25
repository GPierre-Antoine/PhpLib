<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 17/07/18
 * Time: 15:03
 */

namespace siwipo\io\Options;


interface OptionVisitor
{
    public function visitFlag(OptionFlag $flag);

    public function visitString(OptionString $string);

    public function visitList(OptionList $string);

    public function visitMapList(OptionMapList $list);
}