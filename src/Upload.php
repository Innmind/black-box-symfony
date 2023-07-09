<?php
declare(strict_types = 1);

namespace Innmind\BlackBox\Symfony;

final class Upload
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
        array $files,
        array $data = [],
        array $headers = [],
    ): Response {
        return $this->request('POST', $uri, $files, $data, $headers);
    }

    /**
     * @param non-empty-string $uri
     */
    private function put(
        string $uri,
        array $files,
        array $data = [],
        array $headers = [],
    ): Response {
        return $this->request('PUT', $uri, $files, $data, $headers);
    }

    /**
     * @param non-empty-string $method
     * @param non-empty-string $uri
     */
    private function request(
        string $method,
        string $uri,
        array $files,
        array $data = [],
        array $headers = [],
    ): Response {
        $headers = \array_merge(
            $headers,
            ['CONTENT_TYPE' => 'multipart/form-data'],
        );

        return $this->app->request(
            $method,
            $uri,
            $data,
            $files,
            $headers,
        );
    }
}
