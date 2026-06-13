<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use Doctrine\ORM\Mapping as ORM;

trait ObjectLocaleEmbeddableTrait
{
    #[ORM\Embedded(class: \App\Objecting\Embeddable\ObjectLocaleEmbeddable::class, columnPrefix: false)]
    private \App\Objecting\Embeddable\ObjectLocaleEmbeddable $objectLocale;

    protected function initializeObjectLocale(?string $objectLocale = null, ?string $objectTimezone = null): void
    {
        $this->objectLocale = new \App\Objecting\Embeddable\ObjectLocaleEmbeddable($objectLocale, $objectTimezone);
    }

    private function objectLocaleEmbeddable(): \App\Objecting\Embeddable\ObjectLocaleEmbeddable
    {
        if (!isset($this->objectLocale)) {
            $this->objectLocale = new \App\Objecting\Embeddable\ObjectLocaleEmbeddable();
        }

        return $this->objectLocale;
    }

    public function getObjectLocale(): string
    {
        return $this->objectLocaleEmbeddable()->getObjectLocale();
    }

    public function setObjectLocale(string $objectLocale): void
    {
        $this->objectLocaleEmbeddable()->setObjectLocale($objectLocale);
    }

    public function getObjectTimezone(): string
    {
        return $this->objectLocaleEmbeddable()->getObjectTimezone();
    }

    public function setObjectTimezone(string $objectTimezone): void
    {
        $this->objectLocaleEmbeddable()->setObjectTimezone($objectTimezone);
    }
}
