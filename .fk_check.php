<?php
$pdo = new PDO('mysql:host=localhost;dbname=cobit2019_bogakbesar;charset=utf8mb4','root','', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$rows = $pdo->query('SELECT DISTINCT respondent_id FROM assessment_answers ORDER BY respondent_id');
echo "Distinct respondent_id in assessment_answers:\n";
foreach ($rows as $row) {
    echo $row['respondent_id'] . "\n";
}

echo "\nRespondent IDs in respondents table:\n";
$rows = $pdo->query('SELECT id FROM respondents ORDER BY id');
foreach ($rows as $row) {
    echo $row['id'] . "\n";
}

echo "\nRows with invalid respondent_id:\n";
$stmt = $pdo->query('SELECT COUNT(*) as cnt FROM assessment_answers a LEFT JOIN respondents r ON a.respondent_id = r.id WHERE r.id IS NULL');
$row = $stmt->fetch();
echo $row['cnt'] . "\n";
