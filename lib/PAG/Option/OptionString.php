<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 17/07/18
 * Time: 15:02
 */

namespace siwipo\io\Options;


class OptionString extends ValuedAbstractOption
{
    public function accept(OptionVisitor $visitor)
    {
        return $visitor->visitString($this);
    }
}