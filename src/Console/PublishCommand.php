<?php

namespace Senna\UI\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    protected $signature = 'senna-ui:publish {name}';
    protected $description = 'Publish senna-ui resources';

    public function handle()
    {
        $name = $this->argument('name');
        $this->call('vendor:publish', ['--provider' => 'Senna\\UI\\UIServiceProvider', '--tag' => $name]);
    }
}
