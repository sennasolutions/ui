<?php

namespace Senna\UI\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

class InstallCommand extends Command
{
    protected $signature = 'senna-ui:install';
    protected $description = 'Link the assets to the public dir and publish config';
    protected Filesystem $fs;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $fs, ApplicationContract $app)
    {
        parent::__construct();

        $this->fs = $fs;
        $this->app = $app;
    }

    public function handle()
    {
        $this->call('vendor:publish', ['--provider' => 'Senna\\UI\\UIServiceProvider', '--tag' => 'config']);

        $from = realpath(__DIR__ . "/../../dist");
        $to = public_path(config('senna.ui.asset_dir'));

        $this->fs->delete($to);
        // $this->fs->deleteDirectories($to);
        $this->relativeLink($from, $to);
        $this->info('Created link: ' . $from . ' => ' . $to . PHP_EOL);
    }

    public function relativeLink($from, $to) {
        $relativeFrom = get_relative_path($to, $from);
        return $this->fs->link($relativeFrom, $to);
    }
}
