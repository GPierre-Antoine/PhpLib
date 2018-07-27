<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 27/07/18
 * Time: 12:10
 */

namespace PAG\Testing\Actions;


abstract class DefaultBefore implements ActionBefore
{

    public function accept(ActionVisitor $visitor)
    {
        $visitor->visitBefore($this);
    }
}