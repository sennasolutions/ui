<?php

namespace Senna\UI\Traits;

use Senna\UI\Delegate;

trait WithDelegate
{
    public $delegate = [];
 
    public function delegateAction($name, $args = [], $returnFirstArgumentOnFail = true) {
        return Delegate::runActionOnDelegate($this->delegate['_class'], $name, $args, $returnFirstArgumentOnFail);
    }

    public function mountWithDelegate($delegate = null) {
        if ($delegate) {
            $this->delegate = [];
            $this->delegate['_class'] = is_string($delegate) || is_array($delegate) ? $delegate : get_class($delegate);
        }
    }
}
