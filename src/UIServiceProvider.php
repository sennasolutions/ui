<?php

namespace Senna\UI;

use Illuminate\Console\Command;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View as FacadesView;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Livewire;
use Senna\UI\Console\ExtendCommand;
// use Senna\UI\Console\InstallCommand;
use Senna\UI\Console\LinkCommand;
use Senna\UI\Console\PublishCommand;
use Senna\UI\Console\ThemeCommand;
use Senna\UI\Delegate;
use Senna\Utils\Addons\Addons;
use Senna\Utils\Addons\AddonServiceProvider;

class UIServiceProvider extends AddonServiceProvider {

    public function getName() : string { return 'Senna UI'; }
    public function getSlug() : string {  return 'ui'; }
    public function getAssetsDir() : string { return __DIR__ . '/../dist/'; }
    public static function getPluginDir($filePath = "") : string { return realpath(__DIR__ . '/../' . $filePath); }

    /**
     * Run the install from senna:install
     *
     * @return void
     */
    public function install(Command $command, array $extra = []) : bool
    {
        $this->publish('config');
        // $command->call('vendor:publish', ['--provider' => 'Senna\\UI\\UIServiceProvider', '--tag' => 'config']);

        $from = realpath(static::getPluginDir('dist'));
        $to = public_path(config('senna.ui.asset_dir'));

        File::delete($to);

        $this->relativeLink($from, $to);

        $command->info('(Re)created link: ' . $from . ' => ' . $to . PHP_EOL);

        return true;
    }

    public function relativeLink($from, $to) {
        $relativeFrom = get_relative_path($to, $from);
        return File::link($relativeFrom, $to);
    }

    public function register()
    {
        Addons::register($this, 10);

        $this->commands([
            // InstallCommand::class,
            PublishCommand::class,
            LinkCommand::class,
            ThemeCommand::class,
            ExtendCommand::class
        ]);

        $this->mergeConfigFrom(__DIR__ . '/../config/senna.ui.php', 'senna.ui');
    }

    public function boot() {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'senna.ui');
        $this->configurePublishing();
        $this->configureComponents();
        $this->includeHelpers(__DIR__ . "/Helpers");

        // Uses the $value property if Livewire is not available in the project
        Blade::directive('safe_entangle', [UIBladeDirectives::class, 'safeEntangle']);
        Blade::directive('safeEntangle', [UIBladeDirectives::class, 'safeEntangle']);;
        Blade::directive('entangleProp', [UIBladeDirectives::class, 'entangleProp']);;
        Blade::directive('wireProps', [UIBladeDirectives::class, 'wireProps']);;
        Blade::directive('wireEvent', [UIBladeDirectives::class, 'wireEvent']);;
        // Blade::directive('wiredProps', [UIBladeDirectives::class, 'wiredProps']);;

        if (!class_exists(Livewire::class)) {
            Blade::directive('this', [UIBladeDirectives::class, 'this']);;
        }

        if (method_exists(ComponentAttributeBag::class, 'macro')) {
            /**
             * Count the values in the attribute bag.
             */
            ComponentAttributeBag::macro('count', function () {
                return count($this->attributes);
            });
            /**
             * Strip a part of a string from the attribute keys
             */
            ComponentAttributeBag::macro('strip', function ($tag) {
                $that = clone $this;

                foreach($that as $key => $value) {
                    $that[str_replace($tag, "", $key)] = $value;
                    unset($that[$key]);
                }

                return $that;
            });
            ComponentAttributeBag::macro('withoutKeysContaining', function ($string) {
                $that = clone $this;

                foreach($that as $key => $value) {
                    if (str_contains($key, $string)) {
                        unset($that[$key]);
                    }
                }

                return $that;
            });
            ComponentAttributeBag::macro('root', function () {
                return $this->withoutKeysContaining("::");
            });
            ComponentAttributeBag::macro('namespace', function ($namespace = null) {
                if (!$namespace) {
                    return $this->root();
                }
                return $this->whereStartsWith($namespace . "::")->strip($namespace . "::");
            });
        }
    }

    public function configurePublishing() {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/senna.ui.php' => config_path('senna.ui.php'),
            ], 'config');
        }

        if ($this->app->runningInConsole()) {
            // Publish views
            // php artisan vendor:publish --provider="Senna\Admin\SennaServiceProvider" --tag="views"
            // $this->publishes([
            //     __DIR__ . '/../resources/views' => resource_path('views/vendor/senna'),
            // ], 'views');

            // Publish assets
            // php artisan vendor:publish --provider="Senna\Admin\SennaServiceProvider" --tag="assets"
            // dd(__DIR__.'/../dist/');
            // $this->publishes([
            //     __DIR__.'/../dist/' => public_path('senna-ui'),
            // ], 'assets');

            // php artisan vendor:publish --provider="Senna\Admin\SennaServiceProvider" --tag="routes"
            // $this->publishes([
            //     __DIR__.'/../routes/' => public_path('senna'),
            // ], 'routes');

            // php artisan vendor:publish --provider="Senna\Admin\SennaServiceProvider" --tag="senna.users"
            // $this->publishes([
            //     // __DIR__. '/Http/Livewire/Senna/UsersAdmin.php' => app_path('Http/Livewire/Senna/UsersAdmin.php'),
            //     __DIR__. '/../resources/views/livewire/senna/users-admin.blade.php' => resource_path('views/vendor/senna/livewire/senna/users-admin.blade.php'),
            // ], 'senna.users');

            // php artisan vendor:publish --provider="Senna\Admin\SennaServiceProvider" --tag="install"
            $this->publishes([
                __DIR__ . '/../resources/views/theme.blade.php' => resource_path('views/vendor/senna.ui/components/ui/theme.blade.php'),
            ], 'theme');

            // Export the migrations
            // if (! class_exists('CreateSennaMigrations')) {
            //     $this->publishes([
            //     __DIR__ . '/../database/migrations/create_senna_migrations.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_senna_migrations.php'),
            //     // you can add any number of migrations here
            //     ], 'migrations');
            // }
        }
    }

    protected function configureComponents()
    {
        // Blade::component(Delegate::class, 'senna.delegate2');
        

        $this->callAfterResolving(BladeCompiler::class, function () {
            foreach (File::allFiles(__DIR__ . "/../resources/views/components") as $file) {
                $component = $file->getRelativePathname();

                $this->registerComponent($component);
            }
        });

        Blade::component(Delegate::class, 'senna.delegate');
    }

    /**
     * Register the given component.
     *
     * @param  string  $component
     * @return void
     */
    protected function registerComponent(string $component)
    {
        $view = str_replace(".blade.php", "", $component);
        $view = str_replace("/", ".", $view);

        Blade::component('senna.ui::components.' . $view, 'senna.' . $view);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views/components/' . $component => resource_path('views/vendor/senna.ui/components/' . $component),
            ], 'components.' . $view);
        }
    }
}
