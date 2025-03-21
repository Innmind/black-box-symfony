<?php
declare(strict_types = 1);

namespace Innmind\BlackBox\Symfony\Bundle\FrameworkBundle\Test;

use Innmind\BlackBox\PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Service\ResetInterface;
use App\Kernel;

abstract class WebTestCase extends TestCase
{
    protected static ?KernelInterface $kernel = null;
    protected static bool $booted = false;

    #[\Override]
    protected function tearDown(): void
    {
        parent::tearDown();
        self::ensureKernelShutdown();
        self::$kernel = null;
        self::$booted = false;
    }

    protected static function bootKernel(): KernelInterface
    {
        self::ensureKernelShutdown();

        $kernel = self::createKernel();
        $kernel->boot();
        self::$kernel = $kernel;
        self::$booted = true;

        return self::$kernel;
    }

    protected static function getContainer(): ContainerInterface
    {
        $kernel = self::$kernel ?? self::bootKernel();

        if (!self::$booted) {
            $kernel = self::bootKernel();
        }

        /** @var ContainerInterface */
        return $kernel->getContainer()->get('test.service_container');
    }

    protected static function createKernel(): KernelInterface
    {
        return new Kernel('test', true);
    }

    protected static function ensureKernelShutdown(): void
    {
        if (null !== self::$kernel) {
            self::$kernel->boot();
            $container = self::$kernel->getContainer();
            self::$kernel->shutdown();
            self::$booted = false;

            if ($container instanceof ResetInterface) {
                $container->reset();
            }

            self::$kernel = null;
        }
    }

    protected static function createClient(): KernelBrowser
    {
        /** @var KernelBrowser */
        return self::bootKernel()->getContainer()->get('test.client');
    }
}
