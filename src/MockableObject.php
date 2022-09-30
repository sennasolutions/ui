<?php

namespace Senna\UI;

use Stringable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\View\ComponentAttributeBag;

class MockableObject implements Htmlable, Stringable
{
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function toHtml()
    {
        return (string) $this;
    }

    public function toString()
    {
        return (string) $this;
    }

    public function __toString()
    {
        return (string) $this->value;
    }

    public function __call($method, $args)
    {
        return $this;
    }

    public function __get($name)
    {
        return $this;
    }
}
