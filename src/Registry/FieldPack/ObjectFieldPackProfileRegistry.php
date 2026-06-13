<?php

declare(strict_types=1);

namespace App\Objecting\Registry\FieldPack;

use App\Objecting\RegistryInterface\FieldPack\ObjectFieldPackProfileRegistryInterface;
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

    public function get(string $nameEntity): ObjectFieldPackProfile
    {
        $all = $this->all();

        if (!isset($all[$nameEntity])) {
            throw new \InvalidArgumentException(sprintf('Unknown Objecting field-pack profile "%s".', $nameEntity));
        }

        return $all[$nameEntity];
    }

    public function has(string $nameEntity): bool
    {
        return isset($this->all()[$nameEntity]);
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
