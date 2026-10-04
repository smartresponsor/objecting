<?php

declare(strict_types=1);

use Symfony\Component\Yaml\Yaml;

require dirname(__DIR__, 2).'/vendor/autoload.php';

$objectingRoot = dirname(__DIR__, 2);
$workspaceRoot = dirname($objectingRoot);
$outputPath = $objectingRoot.'/resources/audit/workspace-entity-kind-audit.generated.yaml';
$excludedRepositories = ['App', 'Objecting', 'Interfacing', 'mcp'];

$repositories = [];
foreach (new DirectoryIterator($workspaceRoot) as $entry) {
    if (!$entry->isDir() || $entry->isDot()) {
        continue;
    }

    $name = $entry->getFilename();
    $path = $entry->getPathname();
    if (in_array($name, $excludedRepositories, true) || !is_file($path.'/composer.json') || !is_dir($path.'/src')) {
        continue;
    }

    $repositories[$name] = $path;
}
ksort($repositories);

$report = [
    'version' => 1,
    'generated_at' => (new DateTimeImmutable())->format(DateTimeInterface::ATOM),
    'scope' => [
        'workspace_root' => str_replace('\\', '/', $workspaceRoot),
        'excluded_repositories' => $excludedRepositories,
        'method' => 'current_source_doctrine_attribute_scan',
    ],
    'classification_policy' => [
        'object' => 'Explicit ObjectEntityInterface or strong addressable-business-object evidence.',
        'relation' => 'Explicit ObjectRelationEntityInterface or strong materialized-association evidence.',
        'ambiguous' => 'Entity semantics require human review before assigning a kind.',
        'capability_hints' => 'Behavioral hints only; they never determine entity kind.',
    ],
    'summary' => [
        'repositories' => 0,
        'entities' => 0,
        'object' => 0,
        'relation' => 0,
        'ambiguous' => 0,
        'declared_kind' => 0,
        'review_required' => 0,
        'record_like_ambiguous' => 0,
        'append_only_candidates' => 0,
        'immutable_candidates' => 0,
    ],
    'components' => [],
];

foreach ($repositories as $component => $repositoryPath) {
    $entities = [];
    $src = $repositoryPath.'/src';
    $iterator = new RecursiveIteratorIterator(
        new RecursiveCallbackFilterIterator(
            new RecursiveDirectoryIterator($src, FilesystemIterator::SKIP_DOTS),
            static function (SplFileInfo $file): bool {
                if (!$file->isDir()) {
                    return true;
                }

                return !in_array($file->getFilename(), ['vendor', 'var', 'node_modules'], true);
            },
        ),
    );

    foreach ($iterator as $file) {
        if (!$file instanceof SplFileInfo || !$file->isFile() || 'php' !== strtolower($file->getExtension())) {
            continue;
        }

        $contents = file_get_contents($file->getPathname());
        if (!is_string($contents) || !isDoctrineEntity($contents)) {
            continue;
        }

        $className = phpClassName($contents);
        if (null === $className) {
            continue;
        }

        $relativePath = str_replace('\\', '/', substr($file->getPathname(), strlen($repositoryPath) + 1));
        $classification = classifyEntity($className, $contents);
        $entities[] = [
            'class' => $className,
            'path' => $relativePath,
            'kind' => $classification['kind'],
            'confidence' => $classification['confidence'],
            'declared_kind' => $classification['declared_kind'],
            'evidence' => $classification['evidence'],
            'capability_hints' => $classification['capability_hints'],
            'review_required' => 'ambiguous' === $classification['kind'] || 'low' === $classification['confidence'],
        ];

        ++$report['summary']['entities'];
        ++$report['summary'][$classification['kind']];
        if (null !== $classification['declared_kind']) {
            ++$report['summary']['declared_kind'];
        }
        if ('ambiguous' === $classification['kind'] || 'low' === $classification['confidence']) {
            ++$report['summary']['review_required'];
        }
        if (in_array('record_or_event_name_pattern', $classification['evidence'], true)) {
            ++$report['summary']['record_like_ambiguous'];
        }
        if (in_array('append_only_candidate', $classification['capability_hints'], true)) {
            ++$report['summary']['append_only_candidates'];
        }
        if (in_array('immutable_candidate', $classification['capability_hints'], true)) {
            ++$report['summary']['immutable_candidates'];
        }
    }

    if ([] === $entities) {
        continue;
    }

    usort($entities, static fn (array $left, array $right): int => $left['class'] <=> $right['class']);
    $componentSummary = ['entities' => count($entities), 'object' => 0, 'relation' => 0, 'ambiguous' => 0, 'review_required' => 0];
    foreach ($entities as $entity) {
        ++$componentSummary[$entity['kind']];
        if ($entity['review_required']) {
            ++$componentSummary['review_required'];
        }
    }

    $report['components'][$component] = [
        'summary' => $componentSummary,
        'entities' => $entities,
    ];
}

$report['summary']['repositories'] = count($report['components']);

$directory = dirname($outputPath);
if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
    throw new RuntimeException(sprintf('Cannot create audit output directory "%s".', $directory));
}

$yaml = Yaml::dump($report, 8, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);
if (false === file_put_contents($outputPath, $yaml)) {
    throw new RuntimeException(sprintf('Cannot write audit output "%s".', $outputPath));
}

echo sprintf(
    "Objecting workspace entity-kind audit generated: %d repositories, %d entities; object=%d relation=%d ambiguous=%d.\n",
    $report['summary']['repositories'],
    $report['summary']['entities'],
    $report['summary']['object'],
    $report['summary']['relation'],
    $report['summary']['ambiguous'],
);
echo str_replace('\\', '/', $outputPath)."\n";

function isDoctrineEntity(string $contents): bool
{
    return 1 === preg_match('/#\[\s*(?:ORM\\\\)?Entity(?:\s*\(|\s*\])/', $contents)
        || 1 === preg_match('/#\[\s*Doctrine\\\\ORM\\\\Mapping\\\\Entity(?:\s*\(|\s*\])/', $contents);
}

function phpClassName(string $contents): ?string
{
    $tokens = token_get_all($contents);
    $count = count($tokens);

    for ($index = 0; $index < $count; ++$index) {
        $token = $tokens[$index];
        if (!is_array($token) || T_CLASS !== $token[0]) {
            continue;
        }

        $previous = previousMeaningfulToken($tokens, $index - 1);
        if (is_array($previous) && in_array($previous[0], [T_DOUBLE_COLON, T_NEW], true)) {
            continue;
        }

        $next = nextMeaningfulToken($tokens, $index + 1);
        if (is_array($next) && T_STRING === $next[0]) {
            return (string) $next[1];
        }
    }

    return null;
}

/**
 * @param array<int, array{int, string, int}|string> $tokens
 *
 * @return array<int, int|string>|string|null
 */
function previousMeaningfulToken(array $tokens, int $index): array|string|null
{
    for (; $index >= 0; --$index) {
        $token = $tokens[$index];
        if (isIgnorableToken($token)) {
            continue;
        }

        return $token;
    }

    return null;
}

/**
 * @param array<int, array{int, string, int}|string> $tokens
 *
 * @return array<int, int|string>|string|null
 */
function nextMeaningfulToken(array $tokens, int $index): array|string|null
{
    $count = count($tokens);
    for (; $index < $count; ++$index) {
        $token = $tokens[$index];
        if (isIgnorableToken($token)) {
            continue;
        }

        return $token;
    }

    return null;
}

/** @param array{int, string, int}|string $token */
function isIgnorableToken(array|string $token): bool
{
    return is_array($token) && in_array($token[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true);
}

/**
 * @return array{
 *     kind: 'object'|'relation'|'ambiguous',
 *     confidence: 'high'|'medium'|'low',
 *     declared_kind: 'object'|'relation'|null,
 *     evidence: list<string>,
 *     capability_hints: list<string>
 * }
 */
function classifyEntity(string $className, string $contents): array
{
    $evidence = [];
    $capabilityHints = [];

    if (str_contains($contents, 'ObjectEntityInterface')) {
        return [
            'kind' => 'object',
            'confidence' => 'high',
            'declared_kind' => 'object',
            'evidence' => ['implements:ObjectEntityInterface'],
            'capability_hints' => capabilityHints($className, $contents),
        ];
    }

    if (str_contains($contents, 'ObjectRelationEntityInterface')) {
        return [
            'kind' => 'relation',
            'confidence' => 'high',
            'declared_kind' => 'relation',
            'evidence' => ['implements:ObjectRelationEntityInterface'],
            'capability_hints' => capabilityHints($className, $contents),
        ];
    }

    $associationCount = preg_match_all('/#\[\s*(?:ORM\\\\)?(?:ManyToOne|OneToOne|ManyToMany)\b/', $contents);
    $toManyCount = preg_match_all('/#\[\s*(?:ORM\\\\)?(?:OneToMany|ManyToMany)\b/', $contents);
    $relationName = 1 === preg_match('/(?:Link|Assignment|Association|Relation|Membership|Mapping|Map|Join|Grant|Effect)Entity$/', $className)
        || 1 === preg_match('/(?:Link|Assignment|Association|Relation|Membership|Mapping|Map|Join|Grant|Effect)$/', $className);
    $recordLikeName = 1 === preg_match('/(?:Event|Log|History|Audit|Snapshot|Outbox|Inbox|Entry|Posting|Transaction|Record|Attempt|Run|Mismatch|Idempotency)(?:Entity)?$/', $className);

    if ($relationName && $associationCount >= 2) {
        $evidence[] = 'relation_name_pattern';
        $evidence[] = 'multiple_owning_associations';

        return [
            'kind' => 'relation',
            'confidence' => 'high',
            'declared_kind' => null,
            'evidence' => $evidence,
            'capability_hints' => capabilityHints($className, $contents),
        ];
    }

    if ($relationName && $associationCount >= 1) {
        $evidence[] = 'relation_name_pattern';
        $evidence[] = 'owning_association';

        return [
            'kind' => 'relation',
            'confidence' => 'medium',
            'declared_kind' => null,
            'evidence' => $evidence,
            'capability_hints' => capabilityHints($className, $contents),
        ];
    }

    if ($recordLikeName) {
        $evidence[] = 'record_or_event_name_pattern';
        if ($associationCount >= 2) {
            $evidence[] = 'multiple_owning_associations';
        }

        return [
            'kind' => 'ambiguous',
            'confidence' => 'low',
            'declared_kind' => null,
            'evidence' => $evidence,
            'capability_hints' => capabilityHints($className, $contents),
        ];
    }

    $addressabilityScore = 0;
    foreach ([
        'ObjectTitleEmbeddableTrait' => 'object_title_trait',
        'object_first_title' => 'object_title_column',
        '$name' => 'name_field',
        '$title' => 'title_field',
        '$label' => 'label_field',
        '$slug' => 'slug_field',
        'object_slug' => 'object_slug_column',
    ] as $needle => $marker) {
        if (str_contains($contents, $needle)) {
            ++$addressabilityScore;
            $evidence[] = $marker;
        }
    }

    if ($addressabilityScore >= 2 || ($addressabilityScore >= 1 && $toManyCount >= 1)) {
        if ($toManyCount >= 1) {
            $evidence[] = 'aggregate_or_collection_association';
        }

        return [
            'kind' => 'object',
            'confidence' => $addressabilityScore >= 2 ? 'medium' : 'low',
            'declared_kind' => null,
            'evidence' => array_values(array_unique($evidence)),
            'capability_hints' => capabilityHints($className, $contents),
        ];
    }

    return [
        'kind' => 'ambiguous',
        'confidence' => 'low',
        'declared_kind' => null,
        'evidence' => [] === $evidence ? ['insufficient_kind_evidence'] : array_values(array_unique($evidence)),
        'capability_hints' => capabilityHints($className, $contents),
    ];
}

/** @return list<string> */
function capabilityHints(string $className, string $contents): array
{
    $hints = [];
    if (str_contains($contents, 'ObjectAppendOnlyInterface') || 1 === preg_match('/(?:Event|Log|Audit|Outbox|Inbox|Posting|Entry)(?:Entity)?$/', $className)) {
        $hints[] = 'append_only_candidate';
    }
    if (str_contains($contents, 'ObjectImmutableInterface') || 1 === preg_match('/(?:Snapshot|Event|Posting|Entry|Record)(?:Entity)?$/', $className)) {
        $hints[] = 'immutable_candidate';
    }

    return array_values(array_unique($hints));
}
