<?php

namespace Senna\UI;

use Livewire\Livewire;

class UIBladeDirectives 
{

    /**
     * This is a helper function to get the value contents of a wire:prop from the livewire component
     * 
     * @example
     * <x-component wire:thing='someVar' />
     * $thing contains the value of $this->someVar
     *
     * @return void
     */
    public static function wireProps()
    {
        return <<<EOT
            <?php 
                foreach(\$__data['attributes']->whereStartsWith("wire:")->getAttributes() as \$__key => \$__value) {
                    \$__key = str_replace("wire:", "", \$__key);
                    if (isset(\$\$__key) && isset(\$this) && \$this instanceof \Livewire\Component) {
                        \$\$__key = Senna\Utils\Helpers\deep_get(\$this, \$__value);
                    }
                }
                unset(\$__key, \$__value);
            ?>
        EOT;
    }

    /**
     * This is a helper function to get the defined value from a wire:prop attribute
     * 
     * @example
     * <x-component wire:thing='someVar' />
     * $thing contains 'someVar'
     *
     * @return void
     */
    public static function wireVars()
    {
        return <<<EOT
            <?php 
                foreach(\$__data['attributes']->whereStartsWith("wire:")->getAttributes() as \$__key => \$__value) {
                    \$__key = str_replace("wire:", "", \$__key);
                    \$\$__key = \$__value;
                }
                unset(\$__key, \$__value);
            ?>
        EOT;
    }


    /**
     *
     * @param [type] $expression
     * @return void
     */
    public static function entangleProp($expression) {
        $param = preg_replace('/[\W]/', '', $expression);

        return static::safeEntangle("wire:" . $param);
    }

    public static function wireMethod($expression) {
        $param = preg_replace('/[\W]/', '', $expression);

        return <<<EOT
            <?php if(\$attributes && \$attributes->has('wire.method:$param') ): ?>
                window.livewire.find('{{ \$_instance->id }}').{{ \$attributes->get('wire.method:$param') }}
            <?php endif; ?>
        EOT;
    }

    /**
     * safeEntangle keeps working if livewire is not in project
     * It will fallback to a scoped variable is existant
     * 
     *     $attributes->wire('something') via attributes
     *     wire:something via attributes (shorthand)
     *     'somethingelse' direct entanglement
     *
     * @param [type] $expression
     * @return void
     */
    public static function safeEntangle($expression) {
        // Parse the expression
        preg_match('/(->wire)\(\s*[\'"](.*?)[\'"]\s*\)/', $expression, $matches);

        $type = !empty($matches[1]) ? $matches[1] : null;; // type (e.g. ->wire)
        $param = !empty($matches[2]) ? $matches[2] : null; // param name (e.g. something)

        // wire:something is a shorthand for $attributes->wire('something')
        if ($type === null && str_contains($expression, "wire:")) {
            $param = str_replace("wire:", "", $expression);
            $param = preg_replace('/[\W]/', '', $param);
            $expression = "\$attributes->wire('$param')";
            $type = "->wire";
        }

        $fallbackExpression = "\$" . $param . " ?? null";

        // Check if Livewire is available
        if (class_exists(Livewire::class)) {
            // The regular entangle directive
            $entangled = \Livewire\LivewireBladeDirectives::entangle($expression);
            $entangledModel = \Livewire\LivewireBladeDirectives::entangle("\$attributes->wire('model')");

            if ($type === null) { // 'somethinglese'
                return $entangled;
            }
       
            // In case wire:something tag is non present embed the scoped variable in js
            $nonEntangled = static::js($fallbackExpression);

            // Check if the wire:something tag is present
            return <<<EOT
                <?php if(\$attributes && (\$attributes->whereStartsWith('wire:$param')->count()  ) ): ?>
                    {$entangled}
                <?php elseif(\$attributes && '$param' === 'value' && (\$attributes->whereStartsWith('wire:model')->count()  ) ): ?>
                    {$entangledModel}
                <?php else: ?>
                    {$nonEntangled}
                <?php endif; ?>
            EOT;
        }

        // Embed the scoped variable in js
        return static::js($fallbackExpression);
    }

    /**
     * The fallback @js function
     *
     * @param string $expression
     * @return string
     */
    public static function js($expression) : string
    {
        return <<<EOT
<?php
    if (is_object({$expression}) || is_array({$expression})) {
        echo "JSON.parse(atob('".base64_encode(json_encode({$expression}))."'))";
    } elseif (is_string({$expression})) {
        echo "'".str_replace("'", "\'", {$expression})."'";
    } else {
        echo json_encode({$expression});
    }
?>
EOT;
    }

    /**
     * Fallback @this function. A proxy object that returns more proxies
     *
     * @return string
     */
    public static function this() : string {
        return "new Proxy({}, {
            get: function ( storage, key ) { return new Proxy({}, this) },
            set: function ( storage, key, val ) { return new Proxy({}, this) }
        })";
    }
}
