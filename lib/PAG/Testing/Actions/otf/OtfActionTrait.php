<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 27/07/18
 * Time: 11:53
 */

namespace PAG\Testing\Actions\otf;


use PHPUnit\Framework\TestSuite;

trait OtfActionTrait
{
    private $callable;

    public function __construct($callable)
    {
        $this->callable = $callable;
    }

    public function __invoke(TestSuite $testSuite)
    {
        return call_user_func($this->callable, $testSuite);
    }
}