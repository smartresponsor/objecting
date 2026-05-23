# Objecting wave 15 — schema mirror contract

This wave adds the schema mirror contract layer after Doctrine mapping readiness.

## Added

- ObjectSchemaMirrorContract / ObjectSchemaMirrorReport.
- ObjectSchemaMirrorContractReporter + mirror interface.
- Consumer schema mirror example.
- Schema mirror gate and docs.

## Boundary

Backend components keep Doctrine migration ownership. Objecting owns reusable `object_*` system columns. Exposing owns API-visible schema mirrors.
