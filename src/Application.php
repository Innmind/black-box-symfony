<?php
declare(strict_types = 1);

namespace Innmind\BlackBox\Symfony;

use Innmind\BlackBox\Runner\Assert;

final class Application
{
    private Assert $assert;
    private ?Browser $browser = null;

    private function __construct(Assert $assert)
    {
        $this->assert = $assert;
    }

    public static function new(Assert $assert): self
    {
        return new self($assert);
    }

    /**
     * @param non-empty-string $uri
     */
    public function get(string $uri, array $headers = []): Response
    {
        return $this->request('GET', $uri);
    }

    /**
     * @param non-empty-string $uri
     */
    public function post(string $uri, array $data = [], array $headers = []): Response
    {
        return $this->request('POST', $uri, $data, [], $headers);
    }

    /**
     * @param non-empty-string $uri
     */
    public function put(string $uri, array $data = [], array $headers = []): Response
    {
        return $this->request('PUT', $uri, $data, [], $headers);
    }

    /**
     * @param non-empty-string $uri
     */
    public function delete(string $uri, array $data = [], array $headers = []): Response
    {
        return $this->request('DELETE', $uri, $data, [], $headers);
    }

    public function upload(): Upload
    {
        return Upload::of($this);
    }

    /**
     * @param non-empty-string $method
     * @param non-empty-string $uri
     */
    public function request(
        string $method,
        string $uri,
        array $post = [],
        array $files = [],
        array $headers = [],
        string $rawBody = '',
    ): Response {
        // Symfony cast all values from this array in the BrowserKit Request to
        // simulate that everything goes through as as string via multipart but
        // it uses a simple cast and it differs from how a real browser will
        // cast booleans, so we do this transformation here
        \array_walk_recursive($post, static function(mixed &$value) {
            if ($value === true) {
                $value = 'true';
            }

            if ($value === false) {
                $value = 'false';
            }
        });

        $client = $this->browser()->client();
        $client->request($method, $uri, $post, $files, $headers, $rawBody);

        return Response::new(
            $this->assert,
            $client->getResponse(),
            $client->getInternalResponse(),
        );
    }

    private function browser(): Browser
    {
        return $this->browser ??= Browser::new();
    }
}
