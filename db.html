<?php
// SQLite connection and schema bootstrap
date_default_timezone_set('Asia/Manila');

$dbPath = __DIR__ . DIRECTORY_SEPARATOR . 'database.sqlite';
$needInit = !file_exists($dbPath);

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec('PRAGMA foreign_keys = ON');

if ($needInit) {
    // Create tables
    $pdo->exec('CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        student_id TEXT NOT NULL UNIQUE,
        name TEXT NOT NULL,
        program TEXT,
        year_level TEXT
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS events (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        event_date TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS attendance (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        event_id INTEGER NOT NULL,
        student_id INTEGER NOT NULL,
        check_in_at TEXT NOT NULL,
        UNIQUE(event_id, student_id),
        FOREIGN KEY(event_id) REFERENCES events(id) ON DELETE CASCADE,
        FOREIGN KEY(student_id) REFERENCES students(id) ON DELETE CASCADE
    )');

    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_attendance_event ON attendance(event_id)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_attendance_student ON attendance(student_id)');
}

function db(): PDO {
    global $pdo;
    return $pdo;
}

function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}


