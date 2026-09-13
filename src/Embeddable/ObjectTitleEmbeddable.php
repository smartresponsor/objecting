<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectTitleEmbeddable
{
    #[ORM\Column(name: 'object_first_title', type: 'string', length: 255, nullable: true)]
    private ?string $firstTitle = null;

    #[ORM\Column(name: 'object_middle_title', type: 'text', nullable: true)]
    private ?string $middleTitle = null;

    #[ORM\Column(name: 'object_last_title', type: 'text', nullable: true)]
    private ?string $lastTitle = null;

    public function __construct(?string $firstTitle = null, ?string $middleTitle = null, ?string $lastTitle = null)
    {
        $this->firstTitle = $firstTitle;
        $this->middleTitle = $middleTitle;
        $this->lastTitle = $lastTitle;
    }

    public function getFirstTitle(): ?string
    {
        return $this->firstTitle;
    }

    public function setFirstTitle(?string $firstTitle): void
    {
        $this->firstTitle = $firstTitle;
    }

    public function getMiddleTitle(): ?string
    {
        return $this->middleTitle;
    }

    public function setMiddleTitle(?string $middleTitle): void
    {
        $this->middleTitle = $middleTitle;
    }

    public function getLastTitle(): ?string
    {
        return $this->lastTitle;
    }

    public function setLastTitle(?string $lastTitle): void
    {
        $this->lastTitle = $lastTitle;
    }

    /** @return array{firstTitle: ?string, middleTitle: ?string, lastTitle: ?string} */
    public function toCanonicalArray(): array
    {
        return [
            'firstTitle' => $this->firstTitle,
            'middleTitle' => $this->middleTitle,
            'lastTitle' => $this->lastTitle,
        ];
    }
}
