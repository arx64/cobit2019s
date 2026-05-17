<?php
$pdo = new PDO('mysql:host=localhost;dbname=cobit2019_bogakbesar;charset=utf8mb4','root','', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
$rows = $pdo->query('SHOW COLUMNS FROM assessment_answers');
foreach ($rows as $row) {
    echo $row['Field'] . "\t" . $row['Type'] . "\t" . $row['Key'] . "\n";
}
