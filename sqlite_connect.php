<?php
date_default_timezone_set('Asia/Tokyo');

try {
    $pdo = new PDO('sqlite:images.db');

    // テーブル作成
    $pdo->exec("CREATE TABLE IF NOT EXISTS images(
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        image_title TEXT NOT NULL,
        image_name TEXT NOT NULL,
        created_at INTEGER NOT NULL
    )");
} catch (Exception $e) {
    exit('DbConnectError:'.$e->getMessage());
}
