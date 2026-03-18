<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

$dataFile = __DIR__ . '/data/projects.json';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$id = $_GET['id'] ?? null;

if (!file_exists($dataFile)) {
    file_put_contents($dataFile, "[]");
}

$raw = file_get_contents($dataFile);
$projects = json_decode($raw ?: '[]', true);
if (!is_array($projects)) {
    $projects = [];
}

if ($method === 'GET') {
    if ($id !== null) {
        foreach ($projects as $project) {
            if (($project['id'] ?? '') === $id) {
                echo json_encode($project, JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        http_response_code(404);
        echo json_encode(['message' => 'Not found'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    usort($projects, static function (array $a, array $b): int {
        return (int)($b['year'] ?? 0) <=> (int)($a['year'] ?? 0);
    });
    echo json_encode($projects, JSON_UNESCAPED_UNICODE);
    exit;
}

$input = json_decode(file_get_contents('php://input') ?: '{}', true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid payload'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method === 'POST') {
    $new = normalize_project($input);
    if ($new === null) {
        http_response_code(422);
        echo json_encode(['message' => 'Validation failed'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($new['id'] === '') {
        $new['id'] = bin2hex(random_bytes(8));
    }

    $projects[] = $new;
    persist($dataFile, $projects);
    http_response_code(201);
    echo json_encode($new, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method === 'PUT') {
    if ($id === null) {
        http_response_code(400);
        echo json_encode(['message' => 'id is required'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $updated = normalize_project(array_merge($input, ['id' => $id]));
    if ($updated === null) {
        http_response_code(422);
        echo json_encode(['message' => 'Validation failed'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    foreach ($projects as $index => $project) {
        if (($project['id'] ?? '') === $id) {
            $projects[$index] = $updated;
            persist($dataFile, $projects);
            echo json_encode($updated, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    http_response_code(404);
    echo json_encode(['message' => 'Not found'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method === 'DELETE') {
    if ($id === null) {
        http_response_code(400);
        echo json_encode(['message' => 'id is required'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $before = count($projects);
    $projects = array_values(array_filter($projects, static function (array $project) use ($id): bool {
        return ($project['id'] ?? '') !== $id;
    }));

    if ($before === count($projects)) {
        http_response_code(404);
        echo json_encode(['message' => 'Not found'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    persist($dataFile, $projects);
    echo json_encode(['message' => 'Deleted'], JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(405);
echo json_encode(['message' => 'Method not allowed'], JSON_UNESCAPED_UNICODE);

function normalize_project(array $data): ?array
{
    $id = trim((string)($data['id'] ?? ''));
    $title = trim((string)($data['title'] ?? ''));
    $year = trim((string)($data['year'] ?? ''));
    $summary = trim((string)($data['summary'] ?? ''));
    $body = trim((string)($data['body'] ?? ''));

    $tagsRaw = $data['tags'] ?? [];
    if (is_string($tagsRaw)) {
        $tagsRaw = explode(',', $tagsRaw);
    }

    $tags = [];
    if (is_array($tagsRaw)) {
        foreach ($tagsRaw as $tag) {
            $trimmed = trim((string)$tag);
            if ($trimmed !== '') {
                $tags[] = $trimmed;
            }
        }
    }

    if ($title === '' || $year === '' || $summary === '' || $body === '') {
        return null;
    }

    return [
        'id' => $id,
        'title' => $title,
        'year' => $year,
        'summary' => $summary,
        'body' => $body,
        'tags' => $tags,
    ];
}

function persist(string $file, array $projects): void
{
    file_put_contents($file, json_encode($projects, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
