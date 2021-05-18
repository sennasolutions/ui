<?php

namespace Senna\UI\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

class ExtendCommand extends Command
{
    protected $signature = 'senna-ui:extend {name}';
    protected $description = 'Publish a component to the vendor dir';
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
        $name = $this->argument('name');
        $this->call('vendor:publish', ['--provider' => 'Senna\\UI\\UIServiceProvider', '--tag' => 'components.' . $name]);
    }

    public function relativeLink($from, $to) {
        $relativeFrom = get_relative_path($to, $from);
        return $this->fs->link($relativeFrom, $to);
    }
}
