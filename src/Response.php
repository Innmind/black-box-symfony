<?php
declare(strict_types = 1);

namespace Innmind\BlackBox\Symfony;

use Innmind\BlackBox\Runner\Assert;
use Symfony\Component\HttpFoundation;
use Symfony\Component\BrowserKit;

final class Response
{
    private Assert $assert;
    private HttpFoundation\Response $response;
    private BrowserKit\Response $internalResponse;

    private function __construct(
        Assert $assert,
        HttpFoundation\Response $response,
        BrowserKit\Response $internalResponse,
    ) {
        $this->assert = $assert;
        $this->response = $response;
        $this->internalResponse = $internalResponse;
    }

    /**
     * @internal
     */
    public static function new(
        Assert $assert,
        HttpFoundation\Response $response,
        BrowserKit\Response $internalResponse,
    ): self {
        return new self($assert, $response, $internalResponse);
    }

    public function raw(): HttpFoundation\Response
    {
        return $this->response;
    }

    /**
     * @param callable(self): void $assert
     */
    public function matches(callable $assert): self
    {
        $assert($this);

        return $this;
    }

    public function statusCode(): Response\StatusCode
    {
        return Response\StatusCode::of(
            $this->assert,
            $this->response,
        );
    }

    public function headers(): Response\Headers
    {
        return Response\Headers::of(
            $this->assert,
            $this->response,
        );
    }

    public function body(): Response\Body
    {
        return Response\Body::of(
            $this->assert,
            $this->response,
            $this->internalResponse,
        );
    }
}
