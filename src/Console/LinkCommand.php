<?php

namespace Senna\UI\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

class LinkCommand extends Command
{
    protected $signature = 'senna-ui:link';
    protected $description = 'Link the ui components to your components dir';
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
        $from = realpath(__DIR__ . "/../../resources/views/components");
        $componentsDir = resource_path('views/components');
        $to = resource_path('views/components/senna');

        if (!is_dir($componentsDir)) {
            mkdir($componentsDir, 0755, true);
        }

        $this->relativeLink($from, $to);
        $this->info('Created link: ' . $from . ' => ' . $to . PHP_EOL);

    }

    public function relativeLink($from, $to) {
        $relativeFrom = get_relative_path($to, $from);
        return $this->fs->link($relativeFrom, $to);
    }
}
