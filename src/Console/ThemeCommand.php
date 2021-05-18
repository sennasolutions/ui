<?php

namespace Senna\UI\Console;

use Illuminate\Console\Command;

class ThemeCommand extends Command
{
    protected $signature = 'senna-ui:theme';
    protected $description = 'Override the default theme vars';

    public function handle()
    {
        $this->call('vendor:publish', ['--provider' => 'Senna\\UI\\UIServiceProvider', '--tag' => 'theme']);
    }

}
