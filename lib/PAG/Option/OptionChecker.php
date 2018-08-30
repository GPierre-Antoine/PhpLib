<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 17/07/18
 * Time: 15:16
 */

namespace siwipo\io\Options;

use PAG\Collection\Collection;
use PAG\IO\Text\TextFormatter;

class OptionChecker implements OptionVisitor
{
    /**
     * @var Collection
     */
    private $argv;
    /**
     * @var Collection
     */
    private $argv_duplicate;

    private $current_binding;
    private $bindings;
    private $help_bindings;
    /**
     * @var TextFormatter
     */
    private $formatter;

    public function __construct(Collection $argv, Collection $bindings, $help_bindings, TextFormatter $formatter)
    {
        $this->argv          = $argv;
        $this->bindings      = $bindings;
        $this->help_bindings = $help_bindings;
        $this->formatter     = $formatter;
    }

    public function check()
    {
        $this->argv_duplicate = $this->argv->duplicate();
        foreach ($this->bindings as $key => $value) {
            if (!$value->isDone() && $this->argv_duplicate->hasValue($key)) {
                $this->current_binding = $key;
                $value->accept($this);
                $value->setDone(true);
                unset($this->argv_duplicate[$key]);
            }
        }
        foreach ($this->bindings as $key => $value) {
            if ($value->isMandatory() && !$value->isDone()) {
                $help    = $this->helpText();
                $keyname = $this->formatter->bold($key);
                throw new \RuntimeException("Unvaluated Option {$value->getName()} with switch «{$keyname}».$help");
            }
        }
    }

    public function helpText()
    {
        $help_text = "";
        if ($this->help_bindings) {
            $help      = $this->formatter->bold(join(', ', $this->help_bindings));
            $help_text = " Use any of [$help] for help";
        }
        return $help_text;
    }

    public function visitFlag(OptionFlag $flag)
    {
        $flag->setValue(!$flag->getValue());
    }

    public function visitString(OptionString $string)
    {
        $index = $this->argv_duplicate->find($this->current_binding);
        if ($index >= 2 + count($this->argv_duplicate)) {
            $help_text = $this->helpText();


            throw new \RuntimeException("Missing argument for string option {$string->getName()}.$help_text");
        }
        $string->setValue($this->argv_duplicate[$index + 1]);
        unset($this->argv_duplicate[$index + 1]);
    }

    public function visitMapList(OptionMapList $list)
    {
        $this->visitList($list);
    }

    public function visitList(OptionList $list)
    {
        $index = $this->argv_duplicate->find($this->current_binding);
        if ($index >= 2 + count($this->argv_duplicate)) {
            throw new \RuntimeException("Missing argument for List option {$list->getName()}");
        }
        $value = $this->argv_duplicate[$index + 1];
        $list->setValue($value);
        unset($this->argv_duplicate[$index + 1]);
    }
}