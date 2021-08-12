<?php

namespace Senna\UI\Traits;

trait WithDelegate
{
    public $delegate = [];
 
    public function delegateAction($name, $args = [], $returnFirstArgumentOnFail = true) {
        if ($this->delegate['_class']) {
            $delegateListeners = $this->delegate['_class']::$delegateListeners ?? [];

            if (isset($delegateListeners[$name]) && method_exists($this->delegate['_class'], $delegateListeners[$name])) {
                $method = $delegateListeners[$name];
                return $this->delegate['_class']::$method(...$args);
            }
        }

        return $returnFirstArgumentOnFail ? ($args[0] ?? null) : null;
    }

    public function callDelegate($name) {
        if (is_subclass_of($this, 'Livewire\Component')) {
            $this->emit($name);
        }
        return $this->delegateAction($name, [$this], false);
    }

    public function mountWithDelegate($delegate = null) {
        if ($delegate) {
            $this->delegate = [];
            $this->delegate['_class'] = is_string($delegate) || is_array($delegate) ? $delegate : get_class($delegate);
        }
    }
}
