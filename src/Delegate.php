<?php

namespace Senna\UI;

use Closure;
use Exception;
use Hamcrest\Core\IsTypeOf;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\View\Component;
use Illuminate\Support\Str;
/**
 * Render some parts on the delegate via @delegate @enddelegate tags
 */
class Delegate extends Component
{
    protected $name;
    protected $delegate;
    protected $data;
    protected $identifier;

    protected $delegateViewPath;
    protected $delegateView;
    protected $delegateSubviews;

    public static $delegateCache = [];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($identifier = '', $name = null, $delegate = null, $data = [])
    {
        $this->identifier = $identifier;
        $this->withAttributes($data);

        if (!$delegate) {
            throw new Exception("Please provide a delegate class");
        }

        $this->delegate = $delegate = is_string($delegate) ? $delegate : get_class($delegate);

        if ( !isset(self::$delegateCache[$delegate]) ) {
            self::$delegateCache[$delegate] = [
                'delegateViewPath' => str_replace(base_path(), "///-/", realpath( ((new $delegate())->render()->getPath() ) )),
                'delegateView' => null,
                'delegateSubviews' => null,
            ];
        }

        $this->name = $name;
        $this->data = $data;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return $this->renderOnDelegate([
            'data' => $this->data,
            'fallback' => view('senna.ui::components.delegate')
        ]);
    }

    /**
     * Check the view for @delegate and @enddelegate
     *
     * @param [type] $slug
     */
    public function checkViewForTemplate($slug) {
        $cache = &self::$delegateCache[$this->delegate];

        if (!$cache['delegateViewPath']) return;

        if (!$cache['delegateView']) {

            $view = str_replace('///-/', base_path(), $cache['delegateViewPath']);
            $cache['delegateView'] = File::get($view);
        }

        if (!$cache['delegateSubviews']) {
            // Remove comments
            $cache['delegateView'] = preg_replace('/{{--[\s\S]*?--}}/s', "", $cache['delegateView']);

            // Find @delegate blocks and extract them
            preg_match_all('/@delegate\(\s*[\'"](.*?)[\'"]\s*\)(.*?)@enddelegate/s', $cache['delegateView'], $output_array);
    
            $cache['delegateSubviews'] = array_combine($output_array[1], $output_array[2]);
        }
        return $cache['delegateSubviews'][$slug] ?? null;
    }

    /**
     * Render the extracted template
     *
     * @param array $options
     */
    public function renderOnDelegate($options = ['data' => [], 'fallback' => '']) {
        extract($options);

        $template = null;

        $studlySlug = fn($str) => Str::studly(Str::slug($str));
        $name = $this->name;

        // row:{slug} => renderSlugRow
        // something => renderSomething
        $methodSplit = explode(":", $name);
        $method = 'render' . (isset($methodSplit[1]) ? $methodSplit[1] . $studlySlug($methodSplit[0]) : $studlySlug($methodSplit[0]));
        $baseMethod = $method;

        $identifierPrefix = $this->identifier ? $this->identifier . ":" : "";

        if ($this->delegate) {
            $name = $identifierPrefix . $name;

            foreach($data as $key => $item) {
                if (!is_string($item)) continue;
                $name = str_replace("{" . $key . "}", $item, $name);
            }

            // Check for row:slug on view
            $template = $this->checkViewForTemplate($name);

            if (!$template) {
                // Check for renderRowSlug on delegate class
                if ($method === "render") return;
                if ($baseMethod === "render") return;

                $listeners = self::findDelegateListeners($this->delegate, $name);
                $lastListener = $listeners[count($listeners) - 1] ?? fn($x) => null;
                $template = $lastListener($name);
            }
        }

        $template = $template !== null ? $template : $fallback;

        if (is_string($template)) {
            return blade_string($template)
                ->with($this->data);
        }

        return $template;
    }

    public function runAction($name, $args = [], $returnFirstArgumentOnFail = true)
    {
        return static::runActionOnDelegate($this->delegate, $name, $args, $returnFirstArgumentOnFail);
    }

    /**
     * Run an action on the delegate class
     *
     * @param [type] $name
     * @param array $args
     * @param boolean $returnFirstArgumentOnFail
     * @return void
     */
    public static function runActionOnDelegate($delegate, $name, $args = [], $returnFirstArgumentOnFail = true) {
        $output = $returnFirstArgumentOnFail ? ($args[0] ?? null) : null;

        foreach(self::findDelegateListeners($delegate, $name) as $listener) {
            $output = $listener(...$args);
        }

        return $output;
    }

    public static function findDelegateListeners($delegate, $name, &$output = [], $maxDepth = 1, $depth = -1)
    {
        $depth++;

        if ($delegate) {
            $parent = get_parent_class($delegate);

            if ($parent && $depth < $maxDepth) {
                $output = self::findDelegateListeners($parent, $name, $output, $maxDepth, $depth);
            }

            $delegateListeners = $delegate::$delegateListeners ?? [];

            if (method_exists($delegate, 'delegateListeners')) {
                $delegateListeners = $delegate::delegateListeners();
            }

            if (isset($delegateListeners[$name])) {
                $delegateAction = $delegateListeners[$name];

                if ($delegateAction instanceof Closure) {
                    $output[] = $delegateAction;
                }
    
                if (is_string($delegateAction) && method_exists($delegate, $delegateAction)) {
                    $method = $delegateAction;
                    $output[] = fn(...$args) => $delegate::$method(...$args);
                }
            }
        }

        return $output;
    }

}
