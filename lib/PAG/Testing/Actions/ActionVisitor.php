<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 27/07/18
 * Time: 11:48
 */

namespace PAG\Testing\Actions;


interface ActionVisitor
{
    public function visitBefore(ActionBefore $before);

    public function visitAfter(ActionAfter $after);
}