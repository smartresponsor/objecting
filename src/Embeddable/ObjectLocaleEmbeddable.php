<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectLocaleEmbeddable
{
    #[ORM\Column(name: 'object_locale', type: 'string', length: 16)]
    private string $objectLocale = 'en_US';

    #[ORM\Column(name: 'object_timezone', type: 'string', length: 64)]
    private string $objectTimezone = 'UTC';

    public function __construct(?string $objectLocale = null, ?string $objectTimezone = null)
    {
        if (null !== $objectLocale) {
            $this->objectLocale = $objectLocale;
        }

        if (null !== $objectTimezone) {
            $this->objectTimezone = $objectTimezone;
        }
    }

    public function getObjectLocale(): string
    {
        return $this->objectLocale;
    }

    public function setObjectLocale(string $objectLocale): void
    {
        $this->objectLocale = $objectLocale;
    }

    public function getObjectTimezone(): string
    {
        return $this->objectTimezone;
    }

    public function setObjectTimezone(string $objectTimezone): void
    {
        $this->objectTimezone = $objectTimezone;
    }
}
