<?php

namespace PAG\Testing;

use PAG\Testing\Actions\Action;
use PAG\Testing\Actions\ActionAfter;
use PAG\Testing\Actions\ActionBefore;
use PAG\Testing\Actions\ActionVisitor;
use PHPUnit\Framework\TestSuite;

/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 27/07/18
 * Time: 11:36
 */
class TestListenerActionRunner extends TestListenerDelegator implements ActionVisitor
{
    /**
     * @var ActionBefore
     */
    private $before;
    /**
     * @var ActionAfter
     */
    private $after;

    public function __construct(Action ... $actions)
    {
        parent::__construct();
        $this->before = [];
        $this->after  = [];
        foreach ($actions as $action) {
            $action->accept($this);
        }
    }


    public function startTestSuite(TestSuite $suite): void
    {
        foreach ($this->before as $action) {
            $action($suite);
        }
    }

    public function endTestSuite(TestSuite $suite): void
    {
        foreach ($this->after as $action) {
            $action($suite);
        }
    }

    public function visitBefore(ActionBefore $before)
    {
        $this->before[] = $before;
    }

    public function visitAfter(ActionAfter $after)
    {
        $this->after[] = $after;
    }
}