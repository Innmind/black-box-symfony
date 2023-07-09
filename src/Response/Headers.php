<?php
declare(strict_types = 1);

namespace Innmind\BlackBox\Symfony\Response;

use Innmind\BlackBox\Runner\Assert;
use Symfony\Component\HttpFoundation;

final class Headers
{
    private Assert $assert;
    private HttpFoundation\Response $response;

    private function __construct(
        Assert $assert,
        HttpFoundation\Response $response,
    ) {
        $this->assert = $assert;
        $this->response = $response;
    }

    /**
     * @internal
     */
    public static function of(
        Assert $assert,
        HttpFoundation\Response $response,
    ): self {
        return new self($assert, $response);
    }

    /**
     * @param non-empty-string $name
     */
    public function contains(string $name): self
    {
        $this->assert->true($this->response->headers->has($name));

        return $this;
    }

    /**
     * @param non-empty-string $name
     */
    public function named(string $name): Header
    {
        $self = $this->contains($name);

        return Header::of($self->assert, $self->response, $name);
    }
}
