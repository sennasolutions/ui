<?php

namespace Senna\UI;

use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Senna\PackageTools\Commands\InstallCommand;
use Senna\PackageTools\Package;
use Senna\PackageTools\PackageServiceProvider;
use Senna\UI\UIBladeDirectives;

use function Senna\Utils\Helpers\relative_link;

class UIServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->prefix('senna')
            ->name('ui')
            ->hasConfigFile("senna.ui")
            ->hasViews()
            // ->hasMigration('create_ui_table')
            ->hasHelperDirectory("Helpers", inGlobalScope: true)
            ->hasViewComponentsDirectory("../resources/views/components", "senna")
            ->hasViewComponentsDirectory("../resources/views/sui", "sui")
            ->hasInstallCommand(function(InstallCommand $command) use($package) {
                $command
                    ->startWith(function() use($package, $command) {
                        // link dist to /senna-ui
                        $from = $package->basePath("../dist");
                        $to =  public_path(config('senna.ui.asset_dir'));

                        relative_link($from, $to);
                        $command->info('Created link: ' . $from . ' => ' . $to . PHP_EOL);

                        // link components
                        $from = $package->basePath("../resources/views/components");
                        $componentsDir = resource_path('views/components');

                        $to = resource_path('views/components/senna');

                        if (!is_dir($componentsDir)) {
                            mkdir($componentsDir, 0755, true);
                        }

                        relative_link($from, $to);
                        
                        $command->info('Created link: ' . $from . ' => ' . $to . PHP_EOL);

                        // link the new sn folder
                        $from = $package->basePath("../resources/views/sui");
                        $componentsDir = resource_path('views/components');

                        $to = resource_path('views/components/sui');

                        if (!is_dir($componentsDir)) {
                            mkdir($componentsDir, 0755, true);
                        }

                        relative_link($from, $to);

                        $command->info('Created link: ' . $from . ' => ' . $to . PHP_EOL);
                    })
                    ->publishConfigFile();
                    // ->publishMigrations()
                    // ->askToRunMigrations()
                    // ->copyAndRegisterServiceProviderInApp()
                    // ->askToStarRepoOnGitHub('your-vendor/your-repo-name')
            })
            // ->hasCommand(UtilsCommand::class)
            ;
    }

    public function packageBooted()
    {
        $this->bootBladeDirectives();
    }

    public function bootBladeDirectives()
    {
        Blade::directive('safe_entangle', [UIBladeDirectives::class, 'safeEntangle']);
        Blade::directive('safeEntangle', [UIBladeDirectives::class, 'safeEntangle']);;
        Blade::directive('entangleProp', [UIBladeDirectives::class, 'entangleProp']);;
        Blade::directive('wireProps', [UIBladeDirectives::class, 'wireProps']);;
        Blade::directive('wireVars', [UIBladeDirectives::class, 'wireVars']);;
        Blade::directive('wireMethod', [UIBladeDirectives::class, 'wireMethod']);;

        if (!class_exists(Livewire::class)) {
            Blade::directive('this', [UIBladeDirectives::class, 'this']);;
        }

        
    }

}
