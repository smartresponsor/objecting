<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

final class ObjectPackageSurface
{
    public const COMPOSER_PACKAGE = 'smart-responsor/objecting';
    public const NAMESPACE_PREFIX = 'App\\Objecting\\';
    public const BUNDLE_CLASS = 'App\\Objecting\\ObjectBundle';
    public const EXTENSION_CLASS = 'App\\Objecting\\DependencyInjection\\ObjectExtension';
    public const EXTENSION_ALIAS = 'objecting';
    public const SERVICE_CONFIG = 'config/services.yaml';
    public const FIELD_PACK_MANIFEST = 'resources/field-pack/manifest.yaml';
    public const TITLE_ALIAS_MANIFEST = 'resources/title-alias/manifest.yaml';
    public const CONSUMER_EXAMPLE = 'resources/consumer/object-field-packs.example.yaml';
    public const BACKEND_MIGRATION_READINESS_EXAMPLE = 'resources/consumer/object-backend-migration-readiness.example.yaml';
    public const BACKEND_MIGRATION_READINESS_DOC = 'docs/integration/objecting-backend-migration-readiness.md';
    public const BACKEND_MIGRATION_READINESS_CHECK = 'tools/test/objecting_backend_migration_readiness_check.php';
    public const BACKEND_ADOPTION_EXAMPLE = 'resources/consumer/object-backend-adoption.example.yaml';
    public const BACKEND_ADOPTION_DOC = 'docs/integration/objecting-backend-adoption-manifest.md';
    public const BACKEND_ADOPTION_CHECK = 'tools/test/objecting_backend_adoption_manifest_check.php';
    public const BACKEND_HANDOFF_EXAMPLE = 'resources/consumer/object-backend-handoff.example.yaml';
    public const BACKEND_HANDOFF_DOC = 'docs/integration/objecting-backend-handoff-contract.md';
    public const BACKEND_HANDOFF_CHECK = 'tools/test/objecting_backend_handoff_contract_check.php';
    public const BACKEND_ADOPTION_PACKET_EXAMPLE = 'resources/consumer/object-backend-adoption-packet.example.yaml';
    public const BACKEND_ADOPTION_PACKET_DOC = 'docs/integration/objecting-backend-adoption-packet.md';
    public const BACKEND_ADOPTION_PACKET_CHECK = 'tools/test/objecting_backend_adoption_packet_check.php';
    public const BACKEND_IMPORT_EXAMPLE = 'resources/consumer/object-backend-import.example.yaml';
    public const BACKEND_IMPORT_DOC = 'docs/integration/objecting-backend-import-contract.md';
    public const BACKEND_IMPORT_CHECK = 'tools/test/objecting_backend_import_contract_check.php';
    public const BACKEND_CLONE_CLEANUP_EXAMPLE = 'resources/consumer/object-backend-clone-cleanup.example.yaml';
    public const BACKEND_CLONE_CLEANUP_DOC = 'docs/integration/objecting-backend-clone-cleanup-contract.md';
    public const BACKEND_CLONE_CLEANUP_CHECK = 'tools/test/objecting_backend_clone_cleanup_contract_check.php';
    public const BACKEND_MIGRATION_COMMAND_EXAMPLE = 'resources/consumer/object-backend-migration-command.example.yaml';
    public const BACKEND_MIGRATION_COMMAND_DOC = 'docs/integration/objecting-backend-migration-command-packet.md';
    public const BACKEND_MIGRATION_COMMAND_CHECK = 'tools/test/objecting_backend_migration_command_packet_check.php';
    public const SIBLING_PILOT_MIGRATION_HANDOFF_EXAMPLE = 'resources/consumer/object-sibling-pilot-migration-handoff.example.yaml';
    public const SIBLING_PILOT_MIGRATION_HANDOFF_DOC = 'docs/integration/objecting-sibling-pilot-migration-handoff.md';
    public const SIBLING_PILOT_MIGRATION_HANDOFF_CHECK = 'tools/test/objecting_sibling_pilot_migration_handoff_check.php';
    public const RELEASE_MANIFEST_EXAMPLE = 'resources/release/objecting-release-manifest.example.yaml';
    public const RELEASE_READINESS_DOC = 'docs/integration/objecting-release-readiness.md';
    public const RELEASE_READINESS_CHECK = 'tools/test/objecting_release_readiness_check.php';
    public const RELEASE_CLOSURE_EXAMPLE = 'resources/release/objecting-release-closure.example.yaml';
    public const RELEASE_CLOSURE_DOC = 'docs/integration/objecting-release-closure.md';
    public const RELEASE_CLOSURE_CHECK = 'tools/test/objecting_release_closure_check.php';
    public const EMBEDDABLE_INITIALIZATION_DOC = 'docs/integration/objecting-embeddable-initialization.md';
    public const EMBEDDABLE_INITIALIZATION_CHECK = 'tools/test/objecting_embeddable_initialization_check.php';
    public const DOCTRINE_MAPPING_EXAMPLE = 'resources/consumer/object-doctrine-mapping.example.yaml';
    public const DOCTRINE_MAPPING_DOC = 'docs/integration/objecting-doctrine-mapping-contract.md';
    public const DOCTRINE_MAPPING_CHECK = 'tools/test/objecting_doctrine_mapping_contract_check.php';
    public const SCHEMA_MIRROR_EXAMPLE = 'resources/consumer/object-schema-mirror.example.yaml';
    public const SCHEMA_MIRROR_DOC = 'docs/integration/objecting-schema-mirror-contract.md';
    public const SCHEMA_MIRROR_CHECK = 'tools/test/objecting_schema_mirror_contract_check.php';
    public const EXPOSING_BRIDGE_EXAMPLE = 'resources/consumer/object-exposing-bridge.example.yaml';
    public const EXPOSING_BRIDGE_DOC = 'docs/integration/objecting-exposing-bridge-contract.md';
    public const EXPOSING_BRIDGE_CHECK = 'tools/test/objecting_exposing_bridge_contract_check.php';
    public const RC_STABILIZATION_EXAMPLE = 'resources/release/objecting-rc-stabilization.example.yaml';
    public const RC_STABILIZATION_DOC = 'docs/integration/objecting-rc-stabilization.md';
    public const RC_STABILIZATION_CHECK = 'tools/test/objecting_rc_stabilization_check.php';
    public const RC_MARKER_EXAMPLE = 'resources/release/objecting-rc1.example.yaml';
    public const RC_MARKER_DOC = 'docs/release/objecting-rc1.md';
    public const RC_MARKER_CHECK = 'tools/test/objecting_rc1_check.php';
    public const RC2_MARKER_EXAMPLE = 'resources/release/objecting-rc2.example.yaml';
    public const RC2_MARKER_DOC = 'docs/release/objecting-rc2.md';
    public const RC2_MARKER_CHECK = 'tools/test/objecting_rc2_check.php';
    public const PLATFORM_CONSTRAINTS_EXAMPLE = 'resources/release/objecting-platform-constraints.example.yaml';
    public const PLATFORM_CONSTRAINTS_DOC = 'docs/integration/objecting-platform-constraints.md';
    public const PLATFORM_CONSTRAINTS_CHECK = 'tools/test/objecting_platform_constraint_check.php';
    public const SYSTEMIC_FIELD_PACKS_DOC = 'docs/integration/objecting-systemic-field-packs.md';
    public const SYSTEMIC_FIELD_PACKS_CHECK = 'tools/test/objecting_systemic_field_pack_check.php';
    public const TITLE_ALIAS_HARDENING_DOC = 'docs/integration/objecting-title-alias-hardening.md';
    public const TITLE_ALIAS_HARDENING_CHECK = 'tools/test/objecting_title_alias_hardening_check.php';
    public const TITLE_ALIAS_GOVERNANCE_EXAMPLE = 'resources/title-alias/object-title-alias-governance.example.yaml';
    public const MIGRATION_TRANSITION_FREEZE_EXAMPLE = 'resources/release/objecting-migration-transition-freeze.example.yaml';
    public const MIGRATION_TRANSITION_FREEZE_DOC = 'docs/release/objecting-migration-transition-freeze.md';
    public const MIGRATION_TRANSITION_FREEZE_CHECK = 'tools/test/objecting_migration_transition_freeze_check.php';

    public const SYMFONY_REQUIRE_PACKAGES = [
        'symfony/config',
        'symfony/dependency-injection',
        'symfony/http-kernel',
        'symfony/uid',
        'symfony/yaml',
    ];

    /** @return array<string, string> */
    public static function all(): array
    {
        return [
            'composer_package' => self::COMPOSER_PACKAGE,
            'namespace_prefix' => self::NAMESPACE_PREFIX,
            'bundle_class' => self::BUNDLE_CLASS,
            'extension_class' => self::EXTENSION_CLASS,
            'extension_alias' => self::EXTENSION_ALIAS,
            'service_config' => self::SERVICE_CONFIG,
            'field_pack_manifest' => self::FIELD_PACK_MANIFEST,
            'title_alias_manifest' => self::TITLE_ALIAS_MANIFEST,
            'consumer_example' => self::CONSUMER_EXAMPLE,
            'backend_migration_readiness_example' => self::BACKEND_MIGRATION_READINESS_EXAMPLE,
            'backend_migration_readiness_doc' => self::BACKEND_MIGRATION_READINESS_DOC,
            'backend_migration_readiness_check' => self::BACKEND_MIGRATION_READINESS_CHECK,
            'backend_adoption_example' => self::BACKEND_ADOPTION_EXAMPLE,
            'backend_adoption_doc' => self::BACKEND_ADOPTION_DOC,
            'backend_adoption_check' => self::BACKEND_ADOPTION_CHECK,
            'backend_handoff_example' => self::BACKEND_HANDOFF_EXAMPLE,
            'backend_handoff_doc' => self::BACKEND_HANDOFF_DOC,
            'backend_handoff_check' => self::BACKEND_HANDOFF_CHECK,
            'backend_adoption_packet_example' => self::BACKEND_ADOPTION_PACKET_EXAMPLE,
            'backend_adoption_packet_doc' => self::BACKEND_ADOPTION_PACKET_DOC,
            'backend_adoption_packet_check' => self::BACKEND_ADOPTION_PACKET_CHECK,
            'backend_import_example' => self::BACKEND_IMPORT_EXAMPLE,
            'backend_import_doc' => self::BACKEND_IMPORT_DOC,
            'backend_import_check' => self::BACKEND_IMPORT_CHECK,
            'backend_clone_cleanup_example' => self::BACKEND_CLONE_CLEANUP_EXAMPLE,
            'backend_clone_cleanup_doc' => self::BACKEND_CLONE_CLEANUP_DOC,
            'backend_clone_cleanup_check' => self::BACKEND_CLONE_CLEANUP_CHECK,
            'backend_migration_command_example' => self::BACKEND_MIGRATION_COMMAND_EXAMPLE,
            'backend_migration_command_doc' => self::BACKEND_MIGRATION_COMMAND_DOC,
            'backend_migration_command_check' => self::BACKEND_MIGRATION_COMMAND_CHECK,
            'sibling_pilot_migration_handoff_example' => self::SIBLING_PILOT_MIGRATION_HANDOFF_EXAMPLE,
            'sibling_pilot_migration_handoff_doc' => self::SIBLING_PILOT_MIGRATION_HANDOFF_DOC,
            'sibling_pilot_migration_handoff_check' => self::SIBLING_PILOT_MIGRATION_HANDOFF_CHECK,
            'release_manifest_example' => self::RELEASE_MANIFEST_EXAMPLE,
            'release_readiness_doc' => self::RELEASE_READINESS_DOC,
            'release_readiness_check' => self::RELEASE_READINESS_CHECK,
            'release_closure_example' => self::RELEASE_CLOSURE_EXAMPLE,
            'release_closure_doc' => self::RELEASE_CLOSURE_DOC,
            'release_closure_check' => self::RELEASE_CLOSURE_CHECK,
            'embeddable_initialization_doc' => self::EMBEDDABLE_INITIALIZATION_DOC,
            'embeddable_initialization_check' => self::EMBEDDABLE_INITIALIZATION_CHECK,
            'doctrine_mapping_example' => self::DOCTRINE_MAPPING_EXAMPLE,
            'doctrine_mapping_doc' => self::DOCTRINE_MAPPING_DOC,
            'doctrine_mapping_check' => self::DOCTRINE_MAPPING_CHECK,
            'schema_mirror_example' => self::SCHEMA_MIRROR_EXAMPLE,
            'schema_mirror_doc' => self::SCHEMA_MIRROR_DOC,
            'schema_mirror_check' => self::SCHEMA_MIRROR_CHECK,
            'exposing_bridge_example' => self::EXPOSING_BRIDGE_EXAMPLE,
            'exposing_bridge_doc' => self::EXPOSING_BRIDGE_DOC,
            'exposing_bridge_check' => self::EXPOSING_BRIDGE_CHECK,
            'rc_stabilization_example' => self::RC_STABILIZATION_EXAMPLE,
            'rc_stabilization_doc' => self::RC_STABILIZATION_DOC,
            'rc_stabilization_check' => self::RC_STABILIZATION_CHECK,
            'rc_marker_example' => self::RC_MARKER_EXAMPLE,
            'rc_marker_doc' => self::RC_MARKER_DOC,
            'rc_marker_check' => self::RC_MARKER_CHECK,
            'rc2_marker_example' => self::RC2_MARKER_EXAMPLE,
            'rc2_marker_doc' => self::RC2_MARKER_DOC,
            'rc2_marker_check' => self::RC2_MARKER_CHECK,
            'platform_constraints_example' => self::PLATFORM_CONSTRAINTS_EXAMPLE,
            'platform_constraints_doc' => self::PLATFORM_CONSTRAINTS_DOC,
            'platform_constraints_check' => self::PLATFORM_CONSTRAINTS_CHECK,
            'systemic_field_packs_doc' => self::SYSTEMIC_FIELD_PACKS_DOC,
            'systemic_field_packs_check' => self::SYSTEMIC_FIELD_PACKS_CHECK,
            'title_alias_hardening_doc' => self::TITLE_ALIAS_HARDENING_DOC,
            'title_alias_hardening_check' => self::TITLE_ALIAS_HARDENING_CHECK,
            'title_alias_governance_example' => self::TITLE_ALIAS_GOVERNANCE_EXAMPLE,
            'migration_transition_freeze_example' => self::MIGRATION_TRANSITION_FREEZE_EXAMPLE,
            'migration_transition_freeze_doc' => self::MIGRATION_TRANSITION_FREEZE_DOC,
            'migration_transition_freeze_check' => self::MIGRATION_TRANSITION_FREEZE_CHECK,
        ];
    }
}
