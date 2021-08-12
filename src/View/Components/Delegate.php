<?php

namespace Senna\UI\View\Components;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\View\Component;
use Illuminate\Support\Str;

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
                // $baseMethod = str_replace("{" . $key . "}", "", $baseMethod);
                // $method = str_replace("{" . $key . "}", $studlySlug($item), $method);
            }

            // Check for row:slug on view
            $template = $this->checkViewForTemplate($name);

            // if (!$template) {
            //     // Check for renderRowSlug on view
            //     $template = $this->checkViewForTemplate($method);
            // }

            if (!$template) {
                // Check for renderRowSlug on delegate class
                if ($method === "render") return;
                if ($baseMethod === "render") return;
                

                $template = $this->delegateAction($name);


                // if (method_exists($this->delegate, $method)) {
                //     $template = $this->delegate::$method($data);
                // }
                // // Check for renderRow on delegate class
                // else if (method_exists($this->delegate, $baseMethod)) {
                //     $template = $this->delegate::$baseMethod($slug, $data);
                // }
            }
        }

        $template = $template !== null ? $template : $fallback;

        if (is_string($template)) {
            return blade_string($template)
                ->with($this->data);
        }

        return $template;
    }

    public function delegateAction($name, $args = [], $returnFirstArgumentOnFail = true) {
        // dump($this->delegate::$delegateListeners);
        if ($this->delegate) {
            $delegateListeners = $this->delegate::$delegateListeners ?? [];

            if (isset($delegateListeners[$name]) && method_exists($this->delegate, $delegateListeners[$name])) {
                $method = $delegateListeners[$name];
                return $this->delegate::$method(...$args);
            }
        }

        return $returnFirstArgumentOnFail ? ($args[0] ?? null) : null;
    }

    public function callDelegate($name, $args = [], $returnFirstArgumentOnFail = true) {
        if ($this->delegate) {
            if (type_exists($this->delegate, $name)) {
                return $this->delegate::$name(...$args);
            }
        }

        return $returnFirstArgumentOnFail ? ($args[0] ?? null) : null;
    }


}
