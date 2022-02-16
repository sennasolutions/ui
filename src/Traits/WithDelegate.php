<?php

namespace Senna\UI\Traits;

use Senna\UI\Delegate;

trait WithDelegate
{
    public $delegate = [];
 
    public function delegateAction($name, $args = [], $returnFirstArgumentOnFail = true) {
        return Delegate::runActionOnDelegate($this->getDelegateClass(), $name, $args, $returnFirstArgumentOnFail);
    }

    public function getDelegateClass()
    {
        return $this->delegate['class'] ?? null;
    }
    public function getDelegate()
    {
        return $this->delegate ?? null;
    }

    /**
     * Two supported ways:
     * 'delegate' => $this
     * 'delegate' => ['class' => $this, 'view' => 'my.view.path']
     *
     * @param [type] $delegate
     * @return void
     */
    public function mountWithDelegate($delegate = null) {
        if ($delegate) {
            $this->delegate = [];

            if (is_object($delegate)) {
                $this->delegate['class'] = get_class($delegate);
            }

            if ((is_array($delegate) && $delegate['class'] ?? null) && is_object($delegate['class'])) {
                $this->delegate['class'] = get_class($delegate['class']);
            }

            if ((is_array($delegate) && $delegate['view'] ?? null)) {
                $this->delegate['view'] = $delegate['view'];
            }
        }
    }
}
