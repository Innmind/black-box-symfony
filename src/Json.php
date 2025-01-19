<?php
declare(strict_types = 1);

namespace Innmind\BlackBox\Symfony;

use Innmind\Json\Json as Encoding;

final class Json
{
    private Application $app;

    private function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @internal
     */
    public static function of(Application $app): self
    {
        return new self($app);
    }

    /**
     * @param non-empty-string $uri
     */
    private function post(
        string $uri,
        array $headers = [],
        ?array $data = null,
    ): Response {
        return $this->request('POST', $uri, $data, $headers);
    }

    /**
     * @param non-empty-string $uri
     */
    private function put(
        string $uri,
        array $headers = [],
        ?array $data = null,
    ): Response {
        return $this->request('PUT', $uri, $data, $headers);
    }

    /**
     * @param non-empty-string $uri
     */
    private function delete(
        string $uri,
        array $headers = [],
        ?array $data = null,
    ): Response {
        return $this->request('DELETE', $uri, $data, $headers);
    }

    /**
     * @param non-empty-string $method
     * @param non-empty-string $uri
     */
    private function request(
        string $method,
        string $uri,
        ?array $data = null,
        array $headers = [],
    ): Response {
        $headers = \array_merge(
            $headers,
            ['CONTENT_TYPE' => 'application/json'],
        );

        return $this->app->request(
            $method,
            $uri,
            [],
            [],
            $headers,
            match ($data) {
                null => '',
                default => Encoding::encode($data),
            },
        );
    }
}
