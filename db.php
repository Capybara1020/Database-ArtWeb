<?php
$link = mysqli_connect(
    getenv('DB_HOST') ?: 'db',
    getenv('DB_USER') ?: 'a1083305',
    getenv('DB_PASS') ?: 'a1083305Checkpoint7',
    getenv('DB_NAME') ?: 'a1083305'
);

if (!$link) {
    die("資料庫連線失敗: " . mysqli_connect_error());
}
