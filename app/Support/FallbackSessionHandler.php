<?php

declare(strict_types=1);

namespace App\Support;

use SessionHandlerInterface;
use Throwable;

class FallbackSessionHandler implements SessionHandlerInterface
{
    private bool $fallback = false;

    public function __construct(
        private readonly SessionHandlerInterface $primary,
        private readonly SessionHandlerInterface $fallbackHandler,
    ) {
    }

    public function open($path, $name): bool
    {
        return $this->run(fn (): bool => $this->primary->open($path, $name), fn (): bool => $this->fallbackHandler->open($path, $name));
    }

    public function close(): bool
    {
        return $this->run(fn (): bool => $this->primary->close(), fn (): bool => $this->fallbackHandler->close());
    }

    public function read($id): string
    {
        return $this->run(fn (): string => $this->primary->read($id), fn (): string => $this->fallbackHandler->read($id));
    }

    public function write($id, $data): bool
    {
        return $this->run(fn (): bool => $this->primary->write($id, $data), fn (): bool => $this->fallbackHandler->write($id, $data));
    }

    public function destroy($id): bool
    {
        return $this->run(fn (): bool => $this->primary->destroy($id), fn (): bool => $this->fallbackHandler->destroy($id));
    }

    public function gc($maxLifetime): int|false
    {
        return $this->run(fn (): int|false => $this->primary->gc($maxLifetime), fn (): int|false => $this->fallbackHandler->gc($maxLifetime));
    }

    private function run(callable $primary, callable $fallback): mixed
    {
        if ($this->fallback) {
            return $fallback();
        }

        try {
            return $primary();
        } catch (Throwable) {
            $this->fallback = true;

            return $fallback();
        }
    }
}
