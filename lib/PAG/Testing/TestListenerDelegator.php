<?php

namespace PAG\Testing;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;
use PHPUnit\Runner\TestHook;
use PHPUnit\Runner\TestListenerAdapter;

/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 27/07/18
 * Time: 11:36
 */
class TestListenerDelegator implements TestListener
{
    /**
     * @var TestListener
     */
    private $forward;

    public function __construct(?TestListener $listener = null)
    {
        if (is_null($listener)) {
            $listener = new TestListenerAdapter;
        }
        $this->forward = $listener;
    }

    public function add(TestHook $hook): void
    {
        $this->forward->add($hook);
    }

    public function startTest(Test $test): void
    {
        $this->forward->startTest($test);
    }

    public function addError(Test $test, \Throwable $t, float $time): void
    {
        $this->forward->addError($test, $t, $time);
    }

    public function addWarning(Test $test, Warning $e, float $time): void
    {
        $this->forward->addWarning($test, $e, $time);
    }

    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        $this->forward->addFailure($test, $e, $time);
    }

    public function addIncompleteTest(Test $test, \Throwable $t, float $time): void
    {
        $this->forward->addIncompleteTest($test, $t, $time);
    }

    public function addRiskyTest(Test $test, \Throwable $t, float $time): void
    {
        $this->forward->addRiskyTest($test, $t, $time);
    }

    public function addSkippedTest(Test $test, \Throwable $t, float $time): void
    {
        $this->forward->addSkippedTest($test, $t, $time);
    }

    public function endTest(Test $test, float $time): void
    {
        $this->forward->endTest($test, $time);
    }

    public function startTestSuite(TestSuite $suite): void
    {
        $this->forward->startTestSuite($suite);
    }

    public function endTestSuite(TestSuite $suite): void
    {
        $this->forward->endTestSuite($suite);
    }
}