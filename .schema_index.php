<?php
$pdo = new PDO('mysql:host=localhost;dbname=cobit2019_bogakbesar;charset=utf8mb4','root','', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
$rows = $pdo->query('SHOW INDEX FROM assessment_answers');
foreach ($rows as $row) {
    echo $row['Key_name'] . "\t" . $row['Non_unique'] . "\t" . $row['Column_name'] . "\n";
}
