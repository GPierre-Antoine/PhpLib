<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 17/07/18
 * Time: 15:12
 */

namespace siwipo\io\Options;


abstract class ValuedAbstractOption extends AbstractOption
{
    private $mandatory;

    public function __construct($name, $description, $mandatory)
    {
        parent::__construct($name, $description);
        $this->mandatory = $mandatory;
    }

    public function isMandatory()
    {
        return $this->mandatory;
    }
}