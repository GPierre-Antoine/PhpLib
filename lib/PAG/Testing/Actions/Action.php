<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 27/07/18
 * Time: 11:48
 */

namespace PAG\Testing\Actions;


use PHPUnit\Framework\TestSuite;

interface Action
{
    public function accept(ActionVisitor $visitor);

    public function __invoke(TestSuite  $testSuite);
}