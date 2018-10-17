<?php

namespace Harmony;

use Psr\Container\ContainerInterface;

class ConfigFactory
{
    public static function create(array $config = []): Config
    {
        return new Config($config);
    }

    private static function fromEnv(string $key, $default = null)
    {
        if ($val = getenv($key)) {
            return $val;
        }

        return $default;
    }

    public static function fromContainer(ContainerInterface $container): Config
    {
        return $container->get(Config::class);
    }
}
