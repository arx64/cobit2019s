<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/models/Process.php';
require_once __DIR__ . '/../app/models/Assessment.php';
require_once __DIR__ . '/../app/models/Result.php';

$db = getDB();
$processModel = new Process();
$assessmentModel = new Assessment();
$resultModel = new Result();

$today = date('Y-m-d');
$processes = $processModel->getAll();
$out = [];
foreach ($processes as $p) {
    $cap = $assessmentModel->calculateCapabilityLevel($p['id'], null, $today);
    $rec = $resultModel->generateRecommendation($cap, 4, $p['code']);
    $out[] = [
        'process_id' => $p['id'],
        'code' => $p['code'],
        'name' => $p['name'],
        'capability' => $cap,
        'gap' => $rec['gap'],
        'level' => $rec['level'],
        'recommendations' => $rec['recommendations']
    ];
}

echo json_encode(['date' => $today, 'results' => $out], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
