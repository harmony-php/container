<?php

namespace Harmony\DI;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

final class ContainerFactory
{
    private $definitions;

    public function __construct(array $frameworkDefinitions = [])
    {
        $this->definitions = $frameworkDefinitions;
    }

    /**
     * @param array $userDefinitions Userland defined class mappings
     * @throws \Exception When something causes the container bootstrapping to fail
     * @return ContainerInterface
     */
    public function create(array $userDefinitions = []): ContainerInterface
    {
        return (new ContainerBuilder)
            ->ignorePhpDocErrors(true)
            ->writeProxiesToFile(
                $isProduction = strtolower(getenv('ENVIRONMENT')) === 'production',
                $isProduction ? 'tmp/proxies' : null)
            ->addDefinitions(array_merge($this->definitions, $userDefinitions))
            ->build();
    }
}
