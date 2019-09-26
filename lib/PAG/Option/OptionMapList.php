<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 17/07/18
 * Time: 15:02
 */

namespace siwipo\io\Options;


use RuntimeException;

class OptionMapList extends OptionList
{
    public function accept(OptionVisitor $visitor)
    {
        return $visitor->visitMapList($this);
    }

    public function setValue($value)
    {
        if (!$this->getPossibleValues()->hasKey($value)) {
            throw new RuntimeException("Invalid value {$value} in  : ["
                                        . $this->getPossibleValues()->keys()->join(', ') . ']');
        }
        $this->value = $this->getPossibleValues()[$value];
    }
}