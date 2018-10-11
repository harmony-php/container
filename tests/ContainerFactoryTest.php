<?php

namespace Harmony\DI;

use DI\Container;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ContainerFactoryTest extends TestCase
{
    public function test_container_can_be_built()
    {
        $this->assertInstanceOf(Container::class, (new ContainerFactory)->create());
    }

    public function test_framework_definitions_are_used_if_no_userland_definition_for_class_exists()
    {
        $container = (new ContainerFactory([
            NotFoundExceptionInterface::class => \DI\factory(function (ContainerInterface $container) {
                return new class implements NotFoundExceptionInterface {
                    public function __toString()
                    {
                        return 'Framework definition';
                    }
                };
            })
        ]))->create();

        $this->assertEquals('Framework definition', (string) $container->get(NotFoundExceptionInterface::class));
    }

    public function test_user_definitions_overwrite_framework_definitions()
    {
        $container = (new ContainerFactory([
            NotFoundExceptionInterface::class => \DI\factory(function (ContainerInterface $container) {
                return new class implements NotFoundExceptionInterface {
                    public function __toString()
                    {
                        return 'Framework definition';
                    }
                };
            })
        ]))->create([
            NotFoundExceptionInterface::class => \DI\factory(function (ContainerInterface $container) {
                return new class implements NotFoundExceptionInterface {
                    public function __toString()
                    {
                        return 'Userland definition';
                    }
                };
            })
        ]);

        $this->assertEquals('Userland definition', (string) $container->get(NotFoundExceptionInterface::class));
    }
}
