<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectLocaleEmbeddable
{
    #[ORM\Column(name: 'locale', type: 'string', length: 16)]
    private string $locale = 'en_US';

    #[ORM\Column(name: 'timezone', type: 'string', length: 64)]
    private string $timezone = 'UTC';

    public function __construct(?string $objectLocale = null, ?string $objectTimezone = null)
    {
        if (null !== $objectLocale) {
            $this->locale = $objectLocale;
        }

        if (null !== $objectTimezone) {
            $this->timezone = $objectTimezone;
        }
    }

    public function getObjectLocale(): string
    {
        return $this->locale;
    }

    public function setObjectLocale(string $objectLocale): void
    {
        $this->locale = $objectLocale;
    }

    public function getObjectTimezone(): string
    {
        return $this->timezone;
    }

    public function setObjectTimezone(string $objectTimezone): void
    {
        $this->timezone = $objectTimezone;
    }
}
