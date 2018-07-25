<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 17/07/18
 * Time: 15:03
 */

namespace siwipo\io\Options;


class OptionFlag extends AbstractOption
{
    public function __construct($name, $default_value, $description)
    {
        parent::__construct($name, $description);
        $this->value = $default_value;
    }

    public function accept(OptionVisitor $visitor)
    {
        $visitor->visitFlag($this);
    }

    public function isMandatory()
    {
        return false;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
}