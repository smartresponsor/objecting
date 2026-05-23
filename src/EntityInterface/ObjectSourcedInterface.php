<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectSourcedInterface
{
    public function getObjectSource(): ?string;

    public function setObjectSource(?string $objectSource): void;

    public function getObjectProvider(): ?string;

    public function setObjectProvider(?string $objectProvider): void;

    public function getObjectExternalId(): ?string;

    public function setObjectExternalId(?string $objectExternalId): void;

    public function getObjectSourceType(): ?string;

    public function setObjectSourceType(?string $objectSourceType): void;
}
