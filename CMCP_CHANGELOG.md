# CMCP execution journal

## 2026-09-13 — Objecting repository implementation

### Baseline

- Workspace: `D:\PhpstormProjects\www\Objecting`.
- Branch at reconnaissance: `release/objecting-field-pack-normalization-20260910`.
- Objecting is a reusable Symfony-oriented system-field package. It owns field-pack vocabulary, embeddables, traits, interfaces, manifests, package diagnostics, and package contracts. It does not own consumer entities, CRUD, presentation, OpenAPI, migrations, or host runtime behavior.
- Existing unrelated work is present under `.gating/`; this run does not modify or stage that surface.
- Required helper contracts consulted: Objecting, Cruding, Viewing, Interfacing, Gating, and Canonization root agent/readme/composer surfaces where applicable.
- Canonization textual rules consulted: Canon007, Canon008, Canon018, Canon022, Canon026, Canon032, Canon035, Canon039, Canon040, Canon041, and Canon042.

### Target-to-canon mapping

- Canon007 / Canon018: `objecting/object` maps to `App\Objecting\ => src/` and `Object*` component vocabulary; current package identity is aligned.
- Canon008: Objecting currently has no production PHP imports from Cruding, Viewing, or Interfacing, so adding those packages as artificial runtime dependencies would violate dependency integrity rather than improve it.
- Canon022: Objecting is a pure reusable library without standalone Symfony application boot surfaces, so the standalone application dependency baseline is not applicable.
- Canon026: current Symfony constraints `^8.0` are below the canonical Symfony 8.1 floor and require RC-critical correction.
- Canon032: Objecting exposes `ObjectBundle`; its consumer registration contract is documented. The repository is not a standalone Symfony application.
- Canon035: no request runtime/container invalidation ownership belongs to this library; no applicable drift identified.
- Canon039: PHPUnit dependency exists, but repository-owned coverage source declaration and persistent branch-coverage execution contract are incomplete.
- Canon040: coverage thresholds are an evidence gate; this run adds the executable evidence producer contract but does not fabricate coverage percentages.
- Canon041 / Canon042: not applicable because Objecting is not a standalone Symfony application and does not own a browser/UI runtime.

### RC-critical workstream

1. Raise Objecting Symfony package constraints and package metadata from `^8.0` to `^8.1`.
2. Harden the platform-constraint manifest, diagnostics, docs, and tests so `^8.0` is rejected and the Symfony 8.1 floor is executable.
3. Add Canon039-compliant PHPUnit source coverage configuration and persistent branch-coverage Composer script.
4. Run targeted platform/tests first, then Objecting quality/static-analysis/test gates and inspect resulting Git state.

### Growth workstream (post-RC)

- Measure line/method/branch coverage using the new standard producer and raise any measured debt toward Canon040 thresholds without expanding Objecting ownership.

### Implementation and verification result

- Raised Objecting's Symfony runtime/Flex floor from `^8.0` to `^8.1` and updated the package platform manifest, reporter, executable gate, release/handoff artifacts, regression tests, and factual documentation.
- Refreshed the package-scoped Composer lock for the five direct Symfony runtime dependencies; all remain on Symfony 8.1 (`config`, `dependency-injection`, and `uid` 8.1.5; `http-kernel` and `yaml` 8.1.6).
- Replaced the stale Ontology-era root `manifest.json` inventory with current Objecting package surfaces.
- Restored the canonical `object_*` physical Doctrine column vocabulary across every Objecting embeddable while retaining `columnPrefix: false`; the Doctrine mapping gate now inspects actual ORM column attributes and rejects unprefixed Objecting physical columns.
- Added explicit PHPUnit production-source coverage configuration and a persistent Xdebug/php-code-coverage path/branch evidence producer.
- `composer validate --strict --check-lock`: PASS.
- `composer test:quality`: PASS after the schema repair.
- `composer test`: PASS, 68 tests / 458 assertions.
- `composer phpstan`: PASS, 222 files analyzed / no errors.
- `composer audit`: PASS, no security vulnerability advisories found.
- Coverage evidence generated at `var/coverage/summary.txt`: Lines 67.89% (2324/3423), Methods 55.13% (435/789), Branches 79.99% (2339/2924). Canon040 therefore reports remediation debt for line/method coverage, but its normative text classifies valid below-target evidence as a warning rather than a hard RC failure; Objecting is not `HIGH_TEST_DEBT` because no high-debt threshold is crossed.
- Objecting does not declare a repository-local Code Memory scope script; the canonical resolver returned `CODE_MEMORY_SCOPE_SCRIPT_NOT_DECLARED`.
- Downstream host finding, intentionally not patched in this bounded task: App dev runtime lock references non-existent `App\\Objecting\\ObjectingBundle` while Objecting publishes `App\\Objecting\\ObjectBundle`; App production runtime lock does not currently include Objecting. This belongs to App/runtime integration, not Objecting ownership.

### Remaining growth/debt

- Raise measured line and method coverage toward Canon040's 80% / 80% targets while maintaining branch coverage above 70%.
- Correct the downstream App runtime-lock Objecting bundle identity/inclusion in the App-owned integration task.

## 2026-09-20 — RC gate consistency hardening

### Baseline

- Re-read Objecting README/composer surfaces plus mandatory Cruding, Viewing, Interfacing, Gating, and Canonization package contracts.
- Canonization textual rules consulted: Canon007, Canon008, Canon018, Canon021, Canon022, Canon023, Canon024, Canon025, Canon026, Canon029, Canon032, Canon039, and Canon044.
- Canon mapping: Objecting remains a reusable field-pack library rather than a standalone application; therefore Canon022/023/025 standalone/path-dependency requirements do not justify adding Cruding, Viewing, or Interfacing runtime dependencies. Canon044 remains directly applicable to Objecting system-field ownership.
- Pre-existing `.gating/` changes are unrelated workspace state and were preserved without modification by this workstream.

### RC-critical workstream

- `composer test:quality` exposed two stale executable checks that assumed every `Object*EmbeddableTrait` wraps an `Object*Embeddable` typed property.
- `ObjectVersionEmbeddableTrait` is the intentional exception: version and etag are scalar-mapped, with `#[ORM\\Version]` on the version property so Doctrine owns optimistic locking.
- Hardened both structural-canon and embeddable-initialization gates to recognize only that exact scalar version/etag shape, retaining strict lazy-embeddable enforcement for every other field-pack trait.

### Verification

- `composer test:quality`: PASS.
- `composer phpstan`: PASS, 222 files, no errors.
- `composer validate --strict --check-lock`: PASS.
- `composer test`: PASS, 68 tests / 462 assertions.
- Existing unrelated `.gating/` modifications remain outside this task's integration scope.

### Growth workstream

- No growth capability was added. Post-RC work remains coverage uplift and consumer migration ergonomics; neither is required to fix this RC gate inconsistency.

## 2026-09-14 — Canon044 RC hardening

### Baseline

- Workspace: `D:\\PhpstormProjects\\www\\Objecting` on `release/objecting-field-pack-normalization-20260910`.
- Reconnaissance found an existing dirty worktree carrying an in-progress entity-native field naming migration plus unrelated `.gating/` synchronization changes. This run preserves that work and does not reset or overwrite it.
- Objecting remains a pure reusable library without standalone Symfony boot surfaces, so Canon022's standalone application dependency baseline is not applicable. Adding Cruding, Viewing, or Interfacing as artificial runtime dependencies would cross the Objecting responsibility boundary.
- Mandatory helper/reference contour consulted: Objecting, Cruding, Viewing, Interfacing, Gating, and the canonical local `Canonization` repository.
- Canonization textual sources consulted for this workstream: `Canon044ObjectingSystemFieldNamingRule.md`, `Canon043DevelopmentComposerDependencyVersionRule.md`, `Canon022StandaloneApplicationDependencyBaselineRule.md`, `CANONICAL_RULES_JOURNAL.md`, and `GUARD_MATRIX.md`.

### Target-to-canon mapping

- Canon044 applies directly to `objecting/object`: logical pack/type ownership remains `object_*` / `Object*`, while Doctrine physical columns and Doctrine-mapped PHP properties must be entity-native.
- Current dirty changes already move physical columns to names such as `created_at`, `uuid`, `status`, and `version`.
- Remaining RC defect: 36 Doctrine-mapped private properties still use `$object*` names across active embeddables, for example `$objectCreatedAt`, `$objectUuid`, and `$objectStatus`.
- Public ownership/type vocabulary and established public methods are not renamed merely because private mapped state is normalized; this avoids an unnecessary compatibility break while satisfying Canon044.
- Cruding generic CRUD ownership, Viewing presentation ownership, and Interfacing shell/template ownership are out of scope and must not be folded into Objecting.

### RC-critical workstream

1. Rename all active Doctrine-mapped Objecting private properties to entity-native PHP names while preserving explicit entity-native Doctrine column names.
2. Harden the Objecting Doctrine mapping executable gate so future `$object*` / `$objecting*` mapped properties fail deterministically.
3. Re-run targeted Doctrine/schema checks, full `composer test:quality`, PHPUnit, PHPStan, Composer validation/audit, and inspect final Git state.

### Growth workstream (post-RC)

- Improve migration/DX reporting for consumer-side forward renames and broaden coverage toward Canon040 thresholds without adding runtime ownership to Objecting.

### Implementation and verification result

- Normalized all active Doctrine-mapped embeddable private properties from `$object*` names to entity-native PHP names while preserving the existing public Objecting API and explicit entity-native physical columns.
- Updated Doctrine metadata integration assertions to the resulting embedded field paths, including `objectAudit.createdAt`, `objectIdentity.uuid`, and `objectVersion.version`.
- Hardened `tools/test/objecting_doctrine_mapping_contract_check.php` so `object_`/`objecting_` physical columns and `$object*`/`$objecting*` Doctrine-mapped PHP properties fail deterministically.
- Corrected stale ownership wording in the responsibility boundary, schema mirror example, and schema mirror reporter so `object_*` remains logical field-pack vocabulary rather than persisted storage vocabulary.
- `composer test:doctrine-mapping`: PASS.
- `composer test:schema-mirror`: PASS.
- `composer test:quality`: PASS.
- `composer test`: PASS, 68 tests / 458 assertions, including Doctrine schema creation, hydration, and round-trip coverage.
- `composer phpstan`: PASS, 222 files / no errors.
- `composer validate --strict --check-lock`: PASS.
- Changed-file PHP lint: PASS for 37 tracked PHP files.
- `composer audit`: PASS, no security vulnerability advisories found.
- Canonical Code Memory scope resolver: `CODE_MEMORY_SCOPE_SCRIPT_NOT_DECLARED`; no repository-declared memory scope is available to update from this workspace.
- Pre-existing `.gating/` modifications remain intentionally outside this run's commit scope.

## 2026-09-23 — Canon054 deterministic identity constraint ownership

### Baseline

- Workspace: `D:\\PhpstormProjects\\www\\Objecting`; branch `release/objecting-field-pack-normalization-20260910`.
- Mandatory contour re-read: Objecting, Cruding, Viewing, Interfacing, Gating, and Canonization root contracts.
- Canonization textual rules consulted: Canon008, Canon022, Canon026, Canon044, Canon053, Canon054, `GUARD_MATRIX.md`, and the 2026-09-23 Canon054 amendment in `CANONICAL_RULES_JOURNAL.md`.
- Current worktree already contains in-progress Objecting-owned Canon054 work in `ObjectIdentityDoctrineMetadataListener`, its unit test, Composer/Gating integration, and a production Composer manifest; these values are preserved and verified rather than overwritten.
- Unrelated/generated `.gating/` state remains outside Objecting product ownership and will not be staged as product code.

### Target-to-canon mapping

- Canon054 applies directly: reusable Objecting identity metadata must produce deterministic table-level unique constraints such as `uniq_<table>_uuid` and `uniq_<table>_slug`, avoiding column-level `unique: true` and Doctrine hash-derived constraint names.
- Canon044 remains coupled to the same surface: persisted fields stay entity-native (`uuid`, `slug`) while `Object*` remains ownership/type vocabulary.
- Canon008 requires any production integration introduced by Objecting to remain fully described by Composer; Cruding/Viewing/Interfacing are reference dependencies for this task, not artificial Objecting runtime dependencies.
- Canon022 is not directly applicable because Objecting itself is a reusable library without standalone Symfony application boot surfaces.
- Canon053 permits the Gating development symlink currently present in `composer.json`.
- Canon026 remains satisfied by PHP `^8.4` and Symfony `^8.1`.

### RC-critical workstream

1. Validate and harden the existing Doctrine metadata listener so Objecting identity embeddable consumers receive deterministic table-level UUID/slug uniqueness exactly once.
2. Verify bundle/service wiring and package manifests so standalone consumers can activate `App\\Objecting\\ObjectBundle` and production metadata remains reproducible.
3. Add or repair regression coverage and documentation where the current runtime contract is not yet explicit.
4. Run targeted tests, full Objecting quality/static-analysis/unit gates, Composer validation/audit, Gating, and inspect final Git/upstream state.

### Growth workstream (post-RC)

- Expand consumer diagnostics that report legacy hash-derived identity constraints and generate migration-ready remediation guidance, without moving consumer schema migration ownership into Objecting.

### Implementation and verification result

- Confirmed Objecting-owned Doctrine metadata policy adds deterministic `uniq_<table>_uuid` and `uniq_<table>_slug` constraints for consumers embedding `ObjectIdentityEmbeddable`, with idempotent application and collision protection.
- Normalized the Doctrine-mapped version trait state to entity-native private properties `$version` and `$etag` while preserving the public `Object*` lifecycle API.
- Added mandatory PHP-CS-Fixer scripts, Gating development integration, production Composer manifest parity, and synchronized the production manifest license metadata with the repository license.
- Removed copied Gating engine/policy content from the consumer `.gating/` product surface by quarantining it outside the tracked product surface; `.gating/` remains artifact-only.
- Updated integration documentation for ObjectBundle metadata-listener activation and consumer migration ownership.
- Fixed the auxiliary Doctrine-listener test symbol so Objecting's own active-symbol prefix gate remains canonical.
- `composer test:quality`: PASS.
- `composer test`: PASS, 70 tests / 466 assertions.
- `composer cs:check`: PASS, 0 / 192 files fixable.
- `composer phpstan`: PASS, 224 / 224 files, no errors.
- `composer validate --strict --check-lock`: PASS.
- `composer audit`: PASS, no security advisories.
- `composer test:coverage`: PASS; Lines 67.82% (2335/3443), Methods 54.49% (431/791), Branches 79.85% (2346/2938). Canon040 therefore remains warning-level line/method coverage debt while branch coverage exceeds its target.
- Gating now passes Canon029, Canon044, Canon052, Canon053, and Canon054 for the implemented Objecting surfaces.

### External executable-canon blockers

- Canon001 remains a Gating/Canonization implementation mismatch: the normative Canon001 rule states that the technical-role root list is not closed and an unknown root is an escalation candidate rather than evidence of violation, while the current Gating executable hard-fails legitimate Objecting roots such as `Decision`, `Diagnostic`, `Embeddable`, and `EntityInterface`.
- Canon025 remains an applicability conflict for this reusable package: Gating requires standalone `bin/console` and `config/bundles.php`, while Objecting is explicitly a reusable library. Adding those boot surfaces would in turn activate Canon022's standalone dependency baseline and create an invalid self-dependency expectation for the Objecting owner package.
- Canon030 remains an applicability conflict: normative Canon030 targets persistence-owning repositories, while current Gating infers persistence ownership from the presence of Doctrine ORM. Objecting owns reusable embeddable metadata but deliberately does not own consumer entities or migrations.
- These three hard failures are therefore not repaired inside Objecting because doing so would violate the component responsibility boundary; they require a Gating/Canonization applicability correction.

## 2026-09-25 — mapped-superclass identity metadata correction

- Downstream Vendoring fresh-kernel schema creation exposed an Objecting-owned metadata lifecycle defect: the identity listener required concrete physical `uuid`/`slug` columns while Doctrine was still loading a mapped superclass.
- `ObjectIdentityDoctrineMetadataListener` now skips physical-column/unique-constraint enforcement for `ClassMetadata::isMappedSuperclass`; concrete identity consumers remain fully validated.
- Added regression coverage proving an identity-bearing mapped superclass is ignored until concrete metadata is built.
- Objecting PHPUnit PASS: 71 tests / 467 assertions; Doctrine mapping contract PASS; changed-PHP lint PASS.
- Downstream Vendoring Doctrine smoke and all fresh-kernel integration partitions pass after this owner fix.
