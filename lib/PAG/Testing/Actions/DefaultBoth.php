<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 27/07/18
 * Time: 12:11
 */

namespace PAG\Testing\Actions;


abstract class DefaultBoth implements ActionBoth
{

    public function accept(ActionVisitor $visitor)
    {
        $visitor->visitBefore($this);
        $visitor->visitAfter($this);
    }
}