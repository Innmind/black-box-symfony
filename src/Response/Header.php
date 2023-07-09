<?php
declare(strict_types = 1);

namespace Innmind\BlackBox\Symfony\Response;

use Innmind\BlackBox\Runner\Assert;
use Symfony\Component\HttpFoundation;

final class Header
{
    private Assert $assert;
    private HttpFoundation\Response $response;
    /** @var non-empty-string */
    private string $name;

    /**
     * @param non-empty-string $name
     */
    private function __construct(
        Assert $assert,
        HttpFoundation\Response $response,
        string $name,
    ) {
        $this->assert = $assert;
        $this->response = $response;
        $this->name = $name;
    }

    /**
     * @internal
     *
     * @param non-empty-string $name
     */
    public static function of(
        Assert $assert,
        HttpFoundation\Response $response,
        string $name,
    ): self {
        return new self($assert, $response, $name);
    }

    public function same(string $value): self
    {
        $this
            ->assert
            ->expected($value)
            ->same($this->response->headers->get($this->name));

        return $this;
    }
}
