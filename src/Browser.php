<?php
declare(strict_types = 1);

namespace Innmind\BlackBox\Symfony;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Contracts\Service\ResetInterface;

/**
 * @internal
 */
final class Browser
{
    private Kernel $kernel;
    private KernelBrowser $client;

    private function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
        /** @var KernelBrowser */
        $this->client = $kernel->getContainer()->get('test.client');
    }

    public function __destruct()
    {
        $this->kernel->boot(); // to reset internal services
        $container = $this->kernel->getContainer();
        $this->kernel->shutdown();

        if ($container instanceof ResetInterface) {
            $container->reset();
        }
    }

    public static function new(): self
    {
        $kernel = new Kernel('test', true);
        $kernel->boot();

        return new self($kernel);
    }

    public function client(): KernelBrowser
    {
        return $this->client;
    }
}
