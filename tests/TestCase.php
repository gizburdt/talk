<?php

namespace Gizburdt\Talk\Test;

use Gizburdt\Talk\TalkServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [TalkServiceProvider::class];
    }
}
