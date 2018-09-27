<?php

namespace PAG\Bridges\Sherlocks;

use RuntimeException;

trait AssertDefined
{
    protected function assertDefined(...$strings):void
    {
        $errors = [];
        foreach ($strings as $string) {
            if (!isset($this->$string) || !$this->$string) {
                $unset[] = $string;
            }
        }
        if ($errors) {
            $s      = count($errors) > 1 ? 's' : '';
            $errors = join(',', $errors);
            throw new RuntimeException("Undefined option$s «{$errors}»");
        }
    }

}