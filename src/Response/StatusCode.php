<?php
declare(strict_types = 1);

namespace Innmind\BlackBox\Symfony\Response;

use Innmind\BlackBox\Runner\Assert;
use Symfony\Component\HttpFoundation;

final class StatusCode
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

    public function is(int $code): self
    {
        $this
            ->assert
            ->expected($code)
            ->same($this->response->getStatusCode());

        return $this;
    }
}
