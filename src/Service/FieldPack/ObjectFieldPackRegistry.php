<?php

declare(strict_types=1);

namespace App\Objecting\Service\FieldPack;

use App\Objecting\Embeddable\ObjectAuditEmbeddable;
use App\Objecting\Embeddable\ObjectCodeEmbeddable;
use App\Objecting\Embeddable\ObjectConfigEmbeddable;
use App\Objecting\Embeddable\ObjectIdentityEmbeddable;
use App\Objecting\Embeddable\ObjectLocaleEmbeddable;
use App\Objecting\Embeddable\ObjectLockEmbeddable;
use App\Objecting\Embeddable\ObjectPublicationEmbeddable;
use App\Objecting\Embeddable\ObjectRestrictionEmbeddable;
use App\Objecting\Embeddable\ObjectSoftDeleteEmbeddable;
use App\Objecting\Embeddable\ObjectTitleEmbeddable;
use App\Objecting\Embeddable\ObjectTokenEmbeddable;
use App\Objecting\Embeddable\ObjectVersionEmbeddable;
use App\Objecting\Embeddable\ObjectWorkflowEmbeddable;
use App\Objecting\EntityInterface\ObjectAuditedInterface;
use App\Objecting\EntityInterface\ObjectCodedInterface;
use App\Objecting\EntityInterface\ObjectConfigurableInterface;
use App\Objecting\EntityInterface\ObjectFingerprintedInterface;
use App\Objecting\EntityInterface\ObjectIdentifiedInterface;
use App\Objecting\EntityInterface\ObjectLocaleAwareInterface;
use App\Objecting\EntityInterface\ObjectLockableInterface;
use App\Objecting\EntityInterface\ObjectPublishableInterface;
use App\Objecting\EntityInterface\ObjectRestrictableInterface;
use App\Objecting\EntityInterface\ObjectScopedInterface;
use App\Objecting\EntityInterface\ObjectSoftDeletableInterface;
use App\Objecting\EntityInterface\ObjectSourcedInterface;
use App\Objecting\EntityInterface\ObjectStatefulInterface;
use App\Objecting\EntityInterface\ObjectTitledInterface;
use App\Objecting\EntityInterface\ObjectTokenizedInterface;
use App\Objecting\EntityInterface\ObjectVersionedInterface;
use App\Objecting\EntityInterface\ObjectWorkflowAwareInterface;
use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectCodeEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectConfigEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectFingerprintEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectLocaleEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectLockEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectPublicationEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectRestrictionEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectScopeEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectSoftDeleteEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectSourceEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectStateEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectTitleEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectTokenEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectVersionEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectWorkflowEmbeddableTrait;
use App\Objecting\ServiceInterface\FieldPack\ObjectFieldPackRegistryInterface;
use App\Objecting\ValueObject\ObjectFieldPackManifest;
use App\Objecting\ValueObject\ObjectFieldPackName;

final class ObjectFieldPackRegistry implements ObjectFieldPackRegistryInterface
{
    /** @var array<string, ObjectFieldPackManifest>|null */
    private ?array $manifests = null;

    public function all(): array
    {
        return $this->manifests ??= $this->build();
    }

    public function get(string $name): ObjectFieldPackManifest
    {
        $all = $this->all();

        if (!isset($all[$name])) {
            throw new \InvalidArgumentException(sprintf('Unknown Objecting field pack "%s".', $name));
        }

        return $all[$name];
    }

    public function has(string $name): bool
    {
        return isset($this->all()[$name]);
    }

    /** @return array<string, ObjectFieldPackManifest> */
    private function build(): array
    {
        return [
            ObjectFieldPackName::IDENTITY => new ObjectFieldPackManifest(ObjectFieldPackName::IDENTITY, ObjectIdentityEmbeddable::class, ObjectIdentityEmbeddableTrait::class, ObjectIdentifiedInterface::class, ['object_uuid', 'object_slug']),
            ObjectFieldPackName::AUDIT => new ObjectFieldPackManifest(ObjectFieldPackName::AUDIT, ObjectAuditEmbeddable::class, ObjectAuditEmbeddableTrait::class, ObjectAuditedInterface::class, ['object_created_at', 'object_updated_at', 'object_created_by', 'object_updated_by']),
            ObjectFieldPackName::TITLE => new ObjectFieldPackManifest(ObjectFieldPackName::TITLE, ObjectTitleEmbeddable::class, ObjectTitleEmbeddableTrait::class, ObjectTitledInterface::class, ['object_first_title', 'object_middle_title', 'object_last_title']),
            ObjectFieldPackName::PUBLICATION => new ObjectFieldPackManifest(ObjectFieldPackName::PUBLICATION, ObjectPublicationEmbeddable::class, ObjectPublicationEmbeddableTrait::class, ObjectPublishableInterface::class, ['object_published', 'object_published_at']),
            ObjectFieldPackName::SOFT_DELETE => new ObjectFieldPackManifest(ObjectFieldPackName::SOFT_DELETE, ObjectSoftDeleteEmbeddable::class, ObjectSoftDeleteEmbeddableTrait::class, ObjectSoftDeletableInterface::class, ['object_deleted', 'object_deleted_at', 'object_deleted_by']),
            ObjectFieldPackName::VERSION => new ObjectFieldPackManifest(ObjectFieldPackName::VERSION, ObjectVersionEmbeddable::class, ObjectVersionEmbeddableTrait::class, ObjectVersionedInterface::class, [ObjectFieldPackName::VERSION, 'object_etag']),
            ObjectFieldPackName::LOCALE => new ObjectFieldPackManifest(ObjectFieldPackName::LOCALE, ObjectLocaleEmbeddable::class, ObjectLocaleEmbeddableTrait::class, ObjectLocaleAwareInterface::class, [ObjectFieldPackName::LOCALE, 'object_timezone']),
            ObjectFieldPackName::TOKEN => new ObjectFieldPackManifest(ObjectFieldPackName::TOKEN, ObjectTokenEmbeddable::class, ObjectTokenEmbeddableTrait::class, ObjectTokenizedInterface::class, [ObjectFieldPackName::TOKEN, 'object_token_expires_at']),
            ObjectFieldPackName::RESTRICTION => new ObjectFieldPackManifest(ObjectFieldPackName::RESTRICTION, ObjectRestrictionEmbeddable::class, ObjectRestrictionEmbeddableTrait::class, ObjectRestrictableInterface::class, ['object_allowed_roles', 'object_ip_whitelist']),
            ObjectFieldPackName::LOCK => new ObjectFieldPackManifest(ObjectFieldPackName::LOCK, ObjectLockEmbeddable::class, ObjectLockEmbeddableTrait::class, ObjectLockableInterface::class, ['object_locked_at', 'object_locked_by']),
            ObjectFieldPackName::WORKFLOW => new ObjectFieldPackManifest(ObjectFieldPackName::WORKFLOW, ObjectWorkflowEmbeddable::class, ObjectWorkflowEmbeddableTrait::class, ObjectWorkflowAwareInterface::class, ['object_workflow_state', 'object_workflow_context']),
            ObjectFieldPackName::CONFIG => new ObjectFieldPackManifest(ObjectFieldPackName::CONFIG, ObjectConfigEmbeddable::class, ObjectConfigEmbeddableTrait::class, ObjectConfigurableInterface::class, [ObjectFieldPackName::CONFIG]),
            ObjectFieldPackName::CODE => new ObjectFieldPackManifest(ObjectFieldPackName::CODE, ObjectCodeEmbeddable::class, ObjectCodeEmbeddableTrait::class, ObjectCodedInterface::class, [ObjectFieldPackName::CODE]),
            ObjectFieldPackName::STATE => new ObjectFieldPackManifest(ObjectFieldPackName::STATE, ObjectStateEmbeddable::class, ObjectStateEmbeddableTrait::class, ObjectStatefulInterface::class, ['object_active', 'object_enabled', 'object_status']),
            ObjectFieldPackName::SOURCE => new ObjectFieldPackManifest(ObjectFieldPackName::SOURCE, ObjectSourceEmbeddable::class, ObjectSourceEmbeddableTrait::class, ObjectSourcedInterface::class, ['object_source', 'object_provider', 'object_external_id', 'object_source_type']),
            ObjectFieldPackName::FINGERPRINT => new ObjectFieldPackManifest(ObjectFieldPackName::FINGERPRINT, ObjectFingerprintEmbeddable::class, ObjectFingerprintEmbeddableTrait::class, ObjectFingerprintedInterface::class, ['object_hash', 'object_checksum', 'object_algorithm']),
            ObjectFieldPackName::SCOPE => new ObjectFieldPackManifest(ObjectFieldPackName::SCOPE, ObjectScopeEmbeddable::class, ObjectScopeEmbeddableTrait::class, ObjectScopedInterface::class, ['object_scope', 'object_tenant', 'object_organization', 'object_owner']),
        ];
    }
}
