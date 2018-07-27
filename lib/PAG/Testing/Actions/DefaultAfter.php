<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 27/07/18
 * Time: 12:10
 */

namespace PAG\Testing\Actions;


abstract class DefaultAfter implements ActionAfter
{

    public function accept(ActionVisitor $visitor)
    {
        $visitor->visitAfter($this);
    }
}