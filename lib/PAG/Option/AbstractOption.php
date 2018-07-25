<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 17/07/18
 * Time: 15:03
 */

namespace siwipo\io\Options;


abstract class AbstractOption
{
    protected $value;
    private   $name;
    private   $description;
    private   $done;

    /**
     * OptionInterface constructor.
     * @param $name
     * @param $description
     */
    public function __construct($name, $description)
    {
        $this->name        = $name;
        $this->description = $description;
        $this->done        = false;
    }

    /**
     * @return bool
     */
    public function isDone()
    {
        return $this->done;
    }

    /**
     * @param bool $done
     */
    public function setDone($done)
    {
        $this->done = $done;
    }

    abstract public function accept(OptionVisitor $visitor);

    abstract public function isMandatory();

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
}