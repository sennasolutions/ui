<?php

namespace Senna\UI;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Livewire;
use Senna\UI\Console\ExtendCommand;
use Senna\UI\Console\InstallCommand;
use Senna\UI\Console\LinkCommand;
use Senna\UI\Console\ThemeCommand;

class UIServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->commands([
            InstallCommand::class,
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
        $this->includeHelpers();

        // Uses the $val property if Livewire is not available in the project
        Blade::directive('safe_entangle', function ($expression) {
            $entangle = class_exists(Livewire::class) ? "<?php if(count(\$attributes->thatStartWith('wire:model')->getAttributes()) > 0): ?>" . \Livewire\LivewireBladeDirectives::entangle($expression) . "<?php endif; ?>" : '';
            return <<<EOT
<?php if (isset(\$value) && \$value !== null): ?><?php echo inject_in_javascript(\$value) ?><?php else: ?>{$entangle}<?php endif; ?>
EOT;
        });

        if (!class_exists(Livewire::class)) {
            Blade::directive('entangle', function () {
                return '<?php echo inject_in_javascript(\$value) ?>';
            });
            Blade::directive('this', function () {
                return "{}";
            });
        }
    }

    public function includeHelpers() {
        foreach (scandir(__DIR__ . "/Helpers") as $helperFile) {
            $path = __DIR__ . "/Helpers/" . $helperFile;

            if (! is_file($path)) {
                continue;
            }

            $function = Str::before($helperFile, '.php');

            if (function_exists($function)) {
                continue;
            }

            require_once $path;
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
            // php artisan vendor:publish --provider="Senna\Core\SennaServiceProvider" --tag="views"
            // $this->publishes([
            //     __DIR__ . '/../resources/views' => resource_path('views/vendor/senna'),
            // ], 'views');

            // Publish assets
            // php artisan vendor:publish --provider="Senna\Core\SennaServiceProvider" --tag="assets"
            // dd(__DIR__.'/../dist/');
            // $this->publishes([
            //     __DIR__.'/../dist/' => public_path('senna-ui'),
            // ], 'assets');

            // php artisan vendor:publish --provider="Senna\Core\SennaServiceProvider" --tag="routes"
            // $this->publishes([
            //     __DIR__.'/../routes/' => public_path('senna'),
            // ], 'routes');

            // php artisan vendor:publish --provider="Senna\Core\SennaServiceProvider" --tag="senna.users"
            // $this->publishes([
            //     // __DIR__. '/Http/Livewire/Senna/UsersAdmin.php' => app_path('Http/Livewire/Senna/UsersAdmin.php'),
            //     __DIR__. '/../resources/views/livewire/senna/users-admin.blade.php' => resource_path('views/vendor/senna/livewire/senna/users-admin.blade.php'),
            // ], 'senna.users');

            // php artisan vendor:publish --provider="Senna\Core\SennaServiceProvider" --tag="install"
            $this->publishes([
                __DIR__ . '/../resources/views/components/ui/theme.blade.php' => resource_path('views/vendor/senna.ui/components/ui/theme.blade.php'),
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
        $this->callAfterResolving(BladeCompiler::class, function () {
            foreach (File::allFiles(__DIR__ . "/../resources/views/components") as $file) {
                $component = $file->getRelativePathname();

                $this->registerComponent($component);
            }
        });
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
