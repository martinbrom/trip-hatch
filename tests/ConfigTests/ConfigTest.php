<?php

namespace Tests\ConfigTests;

use Core\Config\Config;
use Core\Config\Exception\ConfigurationNotExistsException;
use Core\DependencyInjector\DependencyInjector;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testNonExistentConfiguration() {
        $this->expectException(ConfigurationNotExistsException::class);
        $di = new DependencyInjector();
        $di->register(Config::class);
        $config = $di->getService(Config::class);
        $config->get('non-existent-configuration-name');
    }
}
