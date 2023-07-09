<?php
declare(strict_types = 1);

namespace Innmind\BlackBox\Symfony\Response;

use Innmind\BlackBox\Runner\Assert;
use Symfony\Component\HttpFoundation;
use Symfony\Component\BrowserKit;

final class Body
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
    public static function of(
        Assert $assert,
        HttpFoundation\Response $response,
        BrowserKit\Response $internalResponse,
    ): self {
        return new self($assert, $response, $internalResponse);
    }

    public function toString(): string
    {
        $content = $this->response->getContent();

        return match ($content) {
            false => $this->internalResponse->getContent(),
            default => $content,
        };
    }

    public function json(): mixed
    {
        /** @var mixed */
        $json = \json_decode($this->toString(), true);
        $this
            ->assert
            ->expected(\JSON_ERROR_NONE)
            ->same(\json_last_error());

        return $json;
    }
}
