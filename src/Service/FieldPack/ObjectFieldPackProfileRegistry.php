<?php

declare(strict_types=1);

namespace App\Objecting\Service\FieldPack;

use App\Objecting\ServiceInterface\FieldPack\ObjectFieldPackProfileRegistryInterface;
use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectFieldPackProfile;
use App\Objecting\ValueObject\ObjectFieldPackProfileName;

final class ObjectFieldPackProfileRegistry implements ObjectFieldPackProfileRegistryInterface
{
    /** @var array<string, ObjectFieldPackProfile>|null */
    private ?array $profiles = null;

    /** @return array<string, ObjectFieldPackProfile> */
    public function all(): array
    {
        return $this->profiles ??= $this->build();
    }

    public function get(string $name): ObjectFieldPackProfile
    {
        $all = $this->all();

        if (!isset($all[$name])) {
            throw new \InvalidArgumentException(sprintf('Unknown Objecting field-pack profile "%s".', $name));
        }

        return $all[$name];
    }

    public function has(string $name): bool
    {
        return isset($this->all()[$name]);
    }

    /** @return array<string, ObjectFieldPackProfile> */
    private function build(): array
    {
        return [
            ObjectFieldPackProfileName::BASELINE => new ObjectFieldPackProfile(ObjectFieldPackProfileName::BASELINE, [ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE]),
            ObjectFieldPackProfileName::CONTENT => new ObjectFieldPackProfile(ObjectFieldPackProfileName::CONTENT, [ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE, ObjectFieldPackName::PUBLICATION, ObjectFieldPackName::VERSION]),
            ObjectFieldPackProfileName::LIFECYCLE => new ObjectFieldPackProfile(ObjectFieldPackProfileName::LIFECYCLE, [ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::SOFT_DELETE, ObjectFieldPackName::LOCK, ObjectFieldPackName::WORKFLOW, ObjectFieldPackName::VERSION]),
            ObjectFieldPackProfileName::SECURITY => new ObjectFieldPackProfile(ObjectFieldPackProfileName::SECURITY, [ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE, ObjectFieldPackName::TOKEN, ObjectFieldPackName::RESTRICTION, ObjectFieldPackName::VERSION]),
            ObjectFieldPackProfileName::LOCALIZED => new ObjectFieldPackProfile(ObjectFieldPackProfileName::LOCALIZED, [ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE, ObjectFieldPackName::LOCALE, ObjectFieldPackName::VERSION]),
            ObjectFieldPackProfileName::SYSTEMIC => new ObjectFieldPackProfile(ObjectFieldPackProfileName::SYSTEMIC, [ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE, ObjectFieldPackName::STATE, ObjectFieldPackName::SOURCE, ObjectFieldPackName::FINGERPRINT, ObjectFieldPackName::SCOPE, ObjectFieldPackName::VERSION]),
        ];
    }
}
