<?php

namespace Senna\UI\Tests;

use Senna\UI\ShopServiceProvider;
use Livewire\LivewireServiceProvider;
use Senna\Utils\UtilsServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
  // protected function getBasePath()
  // {
  //     return \realpath(__DIR__.'/../../laravel');
  // }

  public function setUp(): void
  {
    parent::setUp();
    // additional setup
  }

  protected function getPackageProviders($app)
  {
    return [
      UtilsServiceProvider::class,
      LivewireServiceProvider::class,
      ShopServiceProvider::class,
    ];
  }

  protected function getEnvironmentSetUp($app)
  {
    // perform environment setup
  }
}