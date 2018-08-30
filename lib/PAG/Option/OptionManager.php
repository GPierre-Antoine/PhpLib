<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 17/07/18
 * Time: 14:39
 */

namespace siwipo\io\Options;


use PAG\Collection\Collection;
use PAG\IO\Text\TextFormatter;
use Psr\Log\LoggerInterface;

class OptionManager
{
    protected $argv;
    protected $bindings;
    protected $help;
    /**
     * @var LoggerInterface
     */
    private $logger;
    private $checkForHelp;
    /**
     * @var TextFormatter
     */
    private $formatter;

    public function __construct(
        LoggerInterface $logger,
        Collection $argv,
        TextFormatter $formatter,
        $checkForHelp = ["-h", "--help"]
    ) {
        $this->argv = $argv;
        $this->bindings = new Collection();
        $this->checkForHelp = $checkForHelp;
        $this->help = false;
        $this->logger = $logger;
        $this->formatter = $formatter;
    }

    public function bind($schema, $option)
    {
        $this->bindings[$schema] = $option;
    }

    public function check()
    {
        if ($this->checkForHelp) {
            foreach ($this->argv as $item) {
                if (in_array($item, $this->checkForHelp)) {
                    $this->makeHelp();

                    return;
                }
            }
        };
        (new OptionChecker($this->argv, $this->bindings, $this->checkForHelp, $this->formatter))->check();
    }

    private function makeHelp()
    {
        $this->help = true;
        (new OptionHelp($this->logger, $this->bindings, $this->formatter))->check();
    }

    public function isHelp()
    {
        return $this->help;
    }
}