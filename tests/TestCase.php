<?php

namespace Senna\UI\Tests;

use Livewire\LivewireServiceProvider;
use Senna\Utils\UtilsServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
  public function setUp(): void
  {
    parent::setUp();
  }

  protected function getPackageProviders($app)
  {
    return [
      UtilsServiceProvider::class,
      LivewireServiceProvider::class
    ];
  }

  protected function getEnvironmentSetUp($app)
  {
    config()->set('database.default', 'testing');
  }
}