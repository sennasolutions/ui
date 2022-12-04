<?php

namespace Senna\UI;

use Closure;
use Exception;
use Hamcrest\Core\IsTypeOf;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\View\Component;
use Illuminate\Support\Str;

use function Senna\Utils\Helpers\get_view_paths;
use function Senna\Utils\Helpers\snap;

/**
 * Render some parts on the delegate via @delegate @enddelegate tags
 */
class Delegate extends Component
{
    protected $name;

    // protected $delegate;
    protected $delegateClass;
    // protected $delegateViewPath;

    protected $data;
    protected $identifier;

    protected $delegateViewContents;
    protected $delegateBlocks;

    public static $delegateCache = [];

    public function getViewPathFromDelegate($delegate)
    {
        $viewPath = null;

        if ($delegate['view'] ?? null) {
            $viewPath = get_view_paths($delegate['view'])[0] ?? null;
            $viewPath .= ".blade.php";
        }
        
        return $viewPath ?? str_replace(base_path(), "///-/", realpath( ((new $delegate['class'])->render()->getPath() ) ));
    }

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($identifier = '', $name = null, ?array $delegate = null, $data = [])
    {
        $this->identifier = $identifier;
        $this->withAttributes($data);

        if ($delegate) {
            $this->delegateClass = $delegate['class'] ?? null;

            if ( !isset(self::$delegateCache[$this->delegateClass]) ) {
                self::$delegateCache[$this->delegateClass] = [
                    'delegateViewPath' => $this->getViewPathFromDelegate($delegate),
                    'delegateViewContents' => null,
                    'delegateBlocks' => null,
                ];
            }
        } else {
            $this->delegateClass = null;
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
        if(!$this->delegateClass) {
            return view('senna.ui::components.delegate');
        }
        
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
        $cache = &self::$delegateCache[$this->delegateClass];

        if (!$cache['delegateViewPath']) return;

        if (!$cache['delegateViewContents']) {
            $view = str_replace('///-/', base_path(), $cache['delegateViewPath']);
            $cache['delegateViewContents'] = File::get($view);
        }

        if (!$cache['delegateBlocks']) {
            // Remove comments
            $cache['delegateViewContents'] = preg_replace('/{{--[\s\S]*?--}}/s', "", $cache['delegateViewContents']);

            // Find @delegate blocks and extract them
            preg_match_all('/@delegate\(\s*[\'"](.*?)[\'"]\s*\)(.*?)@enddelegate/s', $cache['delegateViewContents'], $output_array);
    
            $cache['delegateBlocks'] = array_combine($output_array[1], $output_array[2]);
        }
        return $cache['delegateBlocks'][$slug] ?? null;
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

        if ($this->delegateClass) {
            $name = $identifierPrefix . $name;

            foreach($data as $key => $item) {
                if (!is_string($item)) continue;
                $name = str_replace("{" . $key . "}", $item, $name);
            }

            ray($name);

            // Check for row:slug on view
            $template = $this->checkViewForTemplate($name);

            if (!$template) {
                // Check for renderRowSlug on delegate class
                if ($method === "render") return;
                if ($baseMethod === "render") return;

                $listeners = self::findDelegateListeners($this->delegateClass, $name);
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

    // public function runAction($name, $args = [], $returnFirstArgumentOnFail = true)
    // {
    //     return static::runActionOnDelegate($this->delegateClass, $name, $args, $returnFirstArgumentOnFail);
    // }

    /**
     * Run an action on the delegate class
     *
     * @param [type] $name
     * @param array $args
     * @param boolean $returnFirstArgumentOnFail
     */
    public static function runActionOnDelegate($delegateClass, $name, $args = [], $returnFirstArgumentOnFail = true) {
        $output = $returnFirstArgumentOnFail ? ($args[0] ?? null) : null;
        // $delegate = $caller->getDelegateClass();
        
        if ($delegateClass) {
            $listeners = snap(fn() => self::findDelegateListeners($delegateClass, $name), "findDelegateListeners" . $delegateClass . $name);

            foreach($listeners as $listener) {
                $output = $listener(...$args);
            }
        }

        return $output;
    }

    public static function findDelegateListeners($delegateClass, $name, &$output = [], $maxDepth = 1, $depth = -1)
    {
        $depth++;

        if ($delegateClass) {
            $parent = get_parent_class($delegateClass);

            if ($parent && $depth < $maxDepth) {
                $output = self::findDelegateListeners($parent, $name, $output, $maxDepth, $depth);
            }

            $delegateListeners = $delegateClass::$delegateListeners ?? [];

            if (method_exists($delegateClass, 'delegateListeners')) {
                $delegateListeners = array_merge($delegateListeners, $delegateClass::delegateListeners());
            }

            if (isset($delegateListeners[$name])) {
                $delegateAction = $delegateListeners[$name];

                if ($delegateAction instanceof Closure) {
                    $output[] = $delegateAction;
                }
    
                if (is_string($delegateAction) && method_exists($delegateClass, $delegateAction)) {
                    $method = $delegateAction;
                    $output[] = fn(...$args) => $delegateClass::$method(...$args);
                }
            }
        }

        return $output;
    }

}
