<?php

declare(strict_types=1);

abstract class TestCase
{
    public function run(): array
    {
        $results = [];

        foreach (get_class_methods($this) as $method) {
            if (! str_starts_with($method, 'test')) {
                continue;
            }

            $this->setUp();

            try {
                $this->{$method}();
                $results[] = ['name' => static::class . '::' . $method, 'passed' => true];
            } catch (Throwable $throwable) {
                $results[] = [
                    'name' => static::class . '::' . $method,
                    'passed' => false,
                    'message' => $throwable->getMessage(),
                ];
            } finally {
                $this->tearDown();
            }
        }

        return $results;
    }

    protected function setUp(): void
    {
        $_GET = [];
        $_POST = [];
        $_SERVER = [];
        $_SESSION = [];
    }

    protected function tearDown(): void
    {
    }

    protected function assertTrue(bool $condition, string $message = 'Failed asserting that condition is true.'): void
    {
        if (! $condition) {
            throw new RuntimeException($message);
        }
    }

    protected function assertFalse(bool $condition, string $message = 'Failed asserting that condition is false.'): void
    {
        $this->assertTrue(! $condition, $message);
    }

    protected function assertSame(mixed $expected, mixed $actual, string $message = ''): void
    {
        if ($expected !== $actual) {
            $message = $message !== '' ? $message : sprintf(
                'Failed asserting that %s matches expected %s.',
                var_export($actual, true),
                var_export($expected, true)
            );

            throw new RuntimeException($message);
        }
    }

    protected function assertNotNull(mixed $value, string $message = 'Failed asserting that value is not null.'): void
    {
        if ($value === null) {
            throw new RuntimeException($message);
        }
    }

    protected function assertArrayHasKey(string|int $key, array $array, string $message = ''): void
    {
        if (! array_key_exists($key, $array)) {
            throw new RuntimeException($message !== '' ? $message : sprintf('Missing array key %s.', (string) $key));
        }
    }

    protected function expectRedirect(callable $callback, string $expectedPath, int $expectedStatus = 302): void
    {
        try {
            $callback();
        } catch (RuntimeException $exception) {
            if (! str_starts_with($exception->getMessage(), 'REDIRECT|')) {
                throw $exception;
            }

            [, $path, $status] = explode('|', $exception->getMessage());
            $this->assertSame($expectedPath, $path, 'Unexpected redirect path.');
            $this->assertSame((string) $expectedStatus, $status, 'Unexpected redirect status.');
            return;
        }

        throw new RuntimeException('Expected redirect was not triggered.');
    }
}
