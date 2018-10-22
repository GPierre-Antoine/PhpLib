<?php
/**
 * Created by PhpStorm.
 * User: pierreantoine
 * Date: 22/10/18
 * Time: 10:16
 */

namespace PAG\Collection\Filter;

class RegexFilter implements Filter
{
    private $pattern;

    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    public function filter($item): bool
    {
        return !preg_match($this->pattern, $item);
    }
}