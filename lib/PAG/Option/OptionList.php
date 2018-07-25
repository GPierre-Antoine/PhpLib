<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 17/07/18
 * Time: 15:02
 */

namespace siwipo\io\Options;

use PAG\Collection\Collection;

class OptionList extends ValuedAbstractOption
{
    private $possible_values;

    public function __construct($name, $possible_values, $description, $mandatory)
    {
        parent::__construct($name, $description, $mandatory);
        $this->possible_values = new Collection($possible_values);
    }

    public function accept(OptionVisitor $visitor)
    {
        return $visitor->visitList($this);
    }

    public function setValue($value)
    {
        if (!$this->getPossibleValues()->hasValue($value)) {
            throw new \RuntimeException("Invalid value {$value} in  : ["
                                        . $this->getPossibleValues()->values()->join(', ') . ']');
        }
        parent::setValue($value);
    }

    public function getPossibleValues(): Collection
    {
        return $this->possible_values;
    }
}