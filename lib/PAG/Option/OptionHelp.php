<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 17/07/18
 * Time: 15:16
 */

namespace siwipo\io\options;


use PAG\Collection\Collection;
use PAG\IO\Format\TextFormatter;
use Psr\Log\LoggerInterface;

class OptionHelp implements OptionVisitor
{
    private $bindings;
    /**
     * @var LoggerInterface
     */
    private $logger;
    private $message;
    private $current_key;
    /**
     * @var TextFormatter
     */
    private $formatter;

    public function __construct(LoggerInterface $logger, Collection $bindings, TextFormatter $formatter)
    {
        $this->bindings  = $bindings;
        $this->logger    = $logger;
        $this->formatter = $formatter;
    }

    public function check()
    {
        /** @var AbstractOption[]|Collection $reverse */
        $reverse = $this->bindings->duplicate();
        $reverse->sort(function (AbstractOption $a, AbstractOption $b) {
            return $a->isMandatory() > $b->isMandatory();
        });
        $this->message = "";
        foreach ($reverse as $key => $binding) {
            if ($binding->isDone()) {
                return;
            }
            $this->current_key = $key;
            $binding->accept($this);
            $binding->setDone(true);
        }
        $this->logger->info("Usage : $this->message");
    }

    public function visitFlag(OptionFlag $flag)
    {
        $this->message = " [ {$this->current_key}]{$this->message}";
        $this->log($flag, "Flag option {$flag->getName()}");
    }

    protected function log(AbstractOption $option, $message)
    {
        $this->logger->info(str_pad($this->formatter->bold($this->current_key) . ' ',
                25,
                ' ',
                STR_PAD_RIGHT) . ($option->isMandatory() ? '[Mandatory] '
                : "") . $message . "\n" . $option->getDescription());
    }

    public function visitString(OptionString $string)
    {
        $this->completeMessage($string);
        $this->log($string, "String option {$string->getName()}");
    }

    public function completeMessage(ValuedAbstractOption $option)
    {
        $value   = $this->formatter->underline("value");
        $message = " $this->current_key $value";
        if (!$option->isMandatory()) {
            $message = " [$message]";
        }
        $this->message = $message . " " . $this->message;
    }

    public function visitList(OptionList $list)
    {
        $values = $list->getPossibleValues()->join(', ');
        $this->completeMessage($list);
        $this->log($list, "List option {$list->getName()}, with possible values : [$values]");
    }

    public function visitMapList(OptionMapList $list)
    {
        $values = $list->getPossibleValues()->keys()->join(', ');
        $this->completeMessage($list);
        $this->log($list, "List option {$list->getName()}, with possible values : [$values]");
    }


}