<?php

declare(strict_types=1);

interface JobImporterInterface
{
    public function support(string $fileName): bool;

    public function parse(string $file): array;

    public function match(object $data): object;
}
