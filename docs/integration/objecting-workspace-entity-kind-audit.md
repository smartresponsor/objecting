# Objecting Workspace Entity-Kind Audit

The workspace entity-kind audit inventories Doctrine entities from the current sibling-repository snapshot and prepares the migration into the executable Objecting entity-kind policy.

Run:

```bash
composer audit:entity-kinds
```

The generated machine-readable report is:

```text
resources/audit/workspace-entity-kind-audit.generated.yaml
```

## Classification boundary

The audit recognizes only the entity kinds currently owned by Objecting:

- `object`
- `relation`

Behavior remains orthogonal. Event-, record-, log-, posting-, snapshot-, and similar entities are not promoted into additional entity kinds. The audit may instead emit capability hints such as `immutable_candidate` and `append_only_candidate`.

## Conservative classification

An explicit `ObjectEntityInterface` or `ObjectRelationEntityInterface` declaration is authoritative. Otherwise the scanner uses structural evidence such as canonical title/slug surfaces, aggregate collections, relation-oriented naming, and Doctrine associations.

Insufficient evidence produces `ambiguous`. This is intentional: the generated report is a migration inventory, not permission to mass-assign interfaces from naming alone.

`review_required: true` means that the entity must be classified from its business semantics before a migration changes its interfaces or Objecting field packs.

## Migration use

Each migration wave should:

1. regenerate this report from the current workspace snapshot;
2. select a bounded component or coherent entity group;
3. resolve ambiguous entities from the component's actual model and invariants;
4. apply the selected kind interface and required Objecting field packs;
5. validate Doctrine metadata with `ObjectEntityKindInspector`;
