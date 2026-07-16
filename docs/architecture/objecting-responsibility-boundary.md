# Objecting responsibility boundary

## Decision

Objecting is the reusable provider of universal, business-neutral system-field packs for Doctrine entities.

Objecting owns canonical `object_*` columns, Doctrine embeddables, explicit composition traits and interfaces, field-pack manifests and profiles, package diagnostics, compatibility metadata, and factual documentation.

Consumer components own business Entity classes and tables, primary keys, Doctrine migrations, business relations and validation, repositories, services, DTOs, Forms, serializers, controllers, routes, fixtures, and runtime behavior.

Exposing owns API and OpenAPI projection. Cruding owns generic CRUD entrypoints. Attachment storage and binary lifecycle belong to an attachment-owning component.

## Forbidden ownership

Objecting must not contain:

- business Entity classes;
- Doctrine migration classes or migration configuration;
- controllers, routes, HTTP actions, DTOs, Forms, or repositories;
- serializer groups or OpenAPI ownership;
- file storage, upload, antivirus, or binary-processing behavior;
- Cruding route or controller declarations;
- a universal base Entity or all-packs god trait;
- `/src/Domain/`, Ports and Adapters, or alternative root namespaces;
- hidden Doctrine listeners that mutate consumer entities automatically.

## Attachment boundary

Objecting does not own attachment associations, file metadata, storage providers, upload lifecycle, MIME inspection, antivirus results, variants, or access policy. An attachment-related scalar may enter Objecting only after an ecosystem audit proves that it is universal, business-neutral system metadata.

## Logging and migration boundary

Embeddables remain dependency-free and do not log normal reads, writes, hydration, or lifecycle transitions. Optional logging is limited to diagnostics, manifest loading, inspection, and compatibility services.

Objecting publishes mappings and compatibility contracts. Consumer components create and own forward Doctrine migrations for their tables.

## API documentation boundary

Objecting publishes semantic and schema manifests. It does not depend on NelmioApiDocBundle or own consumer OpenAPI schemas.

```text
Objecting -> semantic and schema manifests
Exposing  -> OpenAPI and Nelmio projection
Consumer  -> DTO, serializer, controller, route, and runtime API
```
