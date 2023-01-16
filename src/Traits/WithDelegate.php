<?php

namespace Senna\UI\Traits;

use Senna\Utils\Components\Delegate;

trait WithDelegate
{
    public $delegate = [];
 
    /**
     * Runs action on delegate
     *
     * @param string $name Name of the action
     * @param array $args Arguments passed to the action
     * @param boolean $returnFirstArgumentOnFail If true, the first argument will be returned if the delegate does not exist
     * @return mixed
     */
    public function delegateAction(string $name, array $args = [], bool $returnFirstArgumentOnFail = true) {
        return Delegate::runActionOnDelegate($this->getDelegateClass(), $name, $args, $returnFirstArgumentOnFail);
    }

    /**
     * Runs action on delegate with sensible defaults. It will automatically run the name through withIdentifier and
     * appends the current component to the argument list.
     *
     * @param string $name Name of the action
     * @param array $args Arguments passed to the action
     * @return mixed The first argument unless something is returned from the delegate
     */
    public function delegate(string $name, ...$args)
    {
        // Always appends this component as the last argument, so it can be referenced
        $args[] = $this;

        // Pass through the withIdentifier method if it exists
        if (method_exists($this, 'withIdentifier')) {
            $name = $this->withIdentifier($name);
        }

        return Delegate::runActionOnDelegate($this->getDelegateClass(), $name, $args, returnFirstArgumentOnFail: true);
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
