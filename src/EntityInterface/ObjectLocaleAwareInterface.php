<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectLocaleAwareInterface
{
    public function getObjectLocale(): string;

    public function setObjectLocale(string $objectLocale): void;

    public function getObjectTimezone(): string;

    public function setObjectTimezone(string $objectTimezone): void;
}
